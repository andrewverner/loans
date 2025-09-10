<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\Loan\CheckEligibility\CheckLoanEligibilityRequestDto;
use App\Application\Loan\Issue\IssueLoanCommand;
use App\Application\Loan\Issue\IssueLoanRequestDto;
use App\Domain\Client\Entity\Client;
use App\Domain\Loan\Rule\EligibilityService;
use App\Infrastructure\Http\Attribute\MapClient;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[\Symfony\Component\Routing\Attribute\Route(path: '/api/loans')]
final class LoanController extends AbstractController
{
    public function __construct(
        private readonly EligibilityService $eligibilityService,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    #[OA\Post(
        path: "/api/loans",
        summary: "Создать клиента",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/CheckLoanEligibilityRequestDto")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Результат проверки"
            ),
            new OA\Response(
                response: 400,
                description: "Ошибка валидации"
            )
        ]
    )]
    #[\Symfony\Component\Routing\Attribute\Route('/{clientPin}/check', methods: ['POST'])]
    public function check(
        #[MapRequestPayload] CheckLoanEligibilityRequestDto $request,
        #[MapClient(field: 'pin')] Client $client,
    ): JsonResponse {
        $result = $this->eligibilityService->check($client, $request);

        return new JsonResponse([
            'approved' => $result->approved,
            'reason' => $result->reason,
            'rateModifier' => $result->rateModifier,
        ]);
    }

    /**
     * @throws ExceptionInterface
     */
    #[\Symfony\Component\Routing\Attribute\Route('/{clientPin}/issue', methods: ['POST'])]
    public function issue(
        #[MapRequestPayload] IssueLoanRequestDto $request,
        #[MapClient(field: 'pin')] Client $client,
    ): JsonResponse {
        $this->messageBus->dispatch(new IssueLoanCommand(
            client: $client,
            name: $request->name,
            amount: $request->amount,
            rate: $request->rate,
            startDate: $request->startDate,
            endDate: $request->endDate,
        ));

        return new JsonResponse([], Response::HTTP_CREATED);
    }
}

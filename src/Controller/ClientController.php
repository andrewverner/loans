<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\Client\Create\CreateClientCommand;
use App\Application\Client\Create\CreateClientRequestDto;
use App\Domain\Client\Entity\Client;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag('Client')]
#[\Symfony\Component\Routing\Attribute\Route(path: '/api/clients')]
final class ClientController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    #[OA\Post(
        path: "/api/clients",
        summary: "Создать клиента",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/CreateClientRequestDto")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Клиент успешно создан"
            ),
            new OA\Response(
                response: 400,
                description: "Ошибка валидации"
            )
        ]
    )]
    #[\Symfony\Component\Routing\Attribute\Route('/', methods: ['POST'])]
    public function create(#[MapRequestPayload] CreateClientRequestDto $request): JsonResponse
    {
        $envelope = $this->messageBus->dispatch(new CreateClientCommand(
            name: $request->name,
            age: $request->age,
            region: $request->region,
            income: $request->income,
            email: $request->email,
            phone: $request->phone,
            score: $request->score,
            pin: $request->pin,
        ));

        /** @var HandledStamp $handled */
        $handled = $envelope->last(HandledStamp::class);
        $client = $handled->getResult();
        assert($client instanceof Client);

        return new JsonResponse(['pin' => $client->getPin()], Response::HTTP_CREATED);
    }
}

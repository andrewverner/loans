<?php

declare(strict_types=1);

namespace App\Application\Loan\Issue;

use App\Application\Loan\CheckEligibility\CheckLoanEligibilityRequestDto;
use App\Application\Notification\Send\SendNotificationCommand;
use App\Domain\Loan\Entity\Loan;
use App\Domain\Loan\Rule\EligibilityService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final readonly class IssueLoanCommandHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EligibilityService $eligibilityService,
        private MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function __invoke(IssueLoanCommand $command): void
    {
        $result = $this->eligibilityService->check(
            client: $command->client,
            loan: new CheckLoanEligibilityRequestDto(
                name: $command->name,
                amount: $command->amount,
                rate: $command->rate,
                startDate: $command->startDate,
                endDate: $command->endDate,
            ),
        );

        if (!$result->approved) {
            throw new Exception('Loan not approved');
        }

        $loan = new Loan();
        $loan->setClient($command->client);
        $loan->setName($command->name);
        $loan->setAmount($command->amount);
        $loan->setRate($command->rate);
        $loan->setStartDate(new DateTimeImmutable($command->startDate));
        $loan->setEndDate(new DateTimeImmutable($command->endDate));

        $this->entityManager->persist($loan);
        $this->entityManager->flush();

        $this->messageBus->dispatch(new SendNotificationCommand(
            message: sprintf(
                'Loan %s approved and issued to %s (%s)',
                $loan->getName(),
                $loan->getClient()->getName(),
                $loan->getClient()->getPin(),
            ),
        ));
    }
}

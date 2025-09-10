<?php

declare(strict_types=1);

namespace App\Application\Notification\Send;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SendNotificationCommandHandler
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(SendNotificationCommand $command): void
    {
        $this->logger->info($command->message);
    }
}

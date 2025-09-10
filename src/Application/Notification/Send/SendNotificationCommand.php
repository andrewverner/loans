<?php

declare(strict_types=1);

namespace App\Application\Notification\Send;

final readonly class SendNotificationCommand
{
    public function __construct(
        public string $message,
    ) {
    }
}

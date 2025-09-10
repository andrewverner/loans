<?php

declare(strict_types=1);

namespace App\Application\Client\Create;

final readonly class CreateClientCommand
{
    public function __construct(
        public string $name,
        public int $age,
        public string $region,
        public int $income,
        public string $email,
        public string $phone,
        public ?int $score,
        public ?int $pin,
    ) {
    }
}

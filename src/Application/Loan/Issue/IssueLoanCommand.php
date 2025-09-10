<?php

declare(strict_types=1);

namespace App\Application\Loan\Issue;

use App\Domain\Client\Entity\Client;

final readonly class IssueLoanCommand
{
    public function __construct(
        public Client $client,
        public string $name,
        public int $amount,
        public int $rate,
        public string $startDate,
        public string $endDate,
    ) {
    }
}

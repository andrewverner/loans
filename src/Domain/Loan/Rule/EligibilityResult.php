<?php

namespace App\Domain\Loan\Rule;

class EligibilityResult
{
    public function __construct(
        public bool $approved,
        public ?string $reason = null,
        public float $rateModifier = 0.0
    ) {
    }
}

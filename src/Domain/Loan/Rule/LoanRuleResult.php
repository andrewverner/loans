<?php

namespace App\Domain\Loan\Rule;

class LoanRuleResult
{
    public function __construct(
        public bool $passed,
        public ?string $message = null,
        public float $rateModifier = 0.0
    ) {
    }
}

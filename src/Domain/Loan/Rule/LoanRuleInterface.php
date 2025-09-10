<?php

namespace App\Domain\Loan\Rule;

use App\Application\Loan\CheckEligibility\CheckLoanEligibilityRequestDto;
use App\Domain\Client\Entity\Client;

interface LoanRuleInterface
{
    public function apply(Client $client, CheckLoanEligibilityRequestDto $loan): LoanRuleResult;
}

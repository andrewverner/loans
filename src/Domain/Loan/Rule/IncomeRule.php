<?php

namespace App\Domain\Loan\Rule;

use App\Application\Loan\CheckEligibility\CheckLoanEligibilityRequestDto;
use App\Domain\Client\Entity\Client;
use Override;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.loan_rule')]
class IncomeRule implements LoanRuleInterface
{
    #[Override]
    public function apply(Client $client, CheckLoanEligibilityRequestDto $loan): LoanRuleResult
    {
        return $client->getIncome() >= 1000
            ? new LoanRuleResult(true)
            : new LoanRuleResult(false, 'Income too low');
    }
}

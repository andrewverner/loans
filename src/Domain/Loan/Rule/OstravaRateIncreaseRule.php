<?php

namespace App\Domain\Loan\Rule;

use App\Application\Loan\CheckEligibility\CheckLoanEligibilityRequestDto;
use App\Domain\Client\Entity\Client;
use Override;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.loan_rule')]
class OstravaRateIncreaseRule implements LoanRuleInterface
{
    #[Override]
    public function apply(Client $client, CheckLoanEligibilityRequestDto $loan): LoanRuleResult
    {
        return $client->getRegion() === 'OS'
            ? new LoanRuleResult(true, rateModifier: 5.0)
            : new LoanRuleResult(true);
    }
}

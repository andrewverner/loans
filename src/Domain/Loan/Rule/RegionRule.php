<?php

namespace App\Domain\Loan\Rule;

use App\Application\Loan\CheckEligibility\CheckLoanEligibilityRequestDto;
use App\Domain\Client\Entity\Client;
use Override;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.loan_rule')]
class RegionRule implements LoanRuleInterface
{
    private const ALLOWED = ['PR', 'BR', 'OS'];

    #[Override]
    public function apply(Client $client, CheckLoanEligibilityRequestDto $loan): LoanRuleResult
    {
        return in_array($client->getRegion(), self::ALLOWED, true)
            ? new LoanRuleResult(true)
            : new LoanRuleResult(false, 'Region not supported');
    }
}

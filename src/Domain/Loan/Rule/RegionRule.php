<?php

namespace App\Domain\Loan\Rule;

use App\Application\Loan\CheckEligibility\CheckLoanEligibilityRequestDto;
use App\Domain\Client\Entity\Client;
use App\Domain\Client\Enum\RegionEnum;
use Override;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use UnitEnum;

#[AutoconfigureTag('app.loan_rule')]
class RegionRule implements LoanRuleInterface
{
    #[Override]
    public function apply(Client $client, CheckLoanEligibilityRequestDto $loan): LoanRuleResult
    {
        $allowedRegions = array_map(
            static fn (UnitEnum $value) => $value->value,
            RegionEnum::cases(),
        );

        return in_array($client->getRegion(), $allowedRegions, true)
            ? new LoanRuleResult(true)
            : new LoanRuleResult(false, 'Region not supported');
    }
}

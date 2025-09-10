<?php

namespace App\Domain\Loan\Rule;

use App\Application\Loan\CheckEligibility\CheckLoanEligibilityRequestDto;
use App\Domain\Client\Entity\Client;
use Override;
use Random\RandomException;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.loan_rule')]
class PragueRandomRefusalRule implements LoanRuleInterface
{
    /**
     * @throws RandomException
     */
    #[Override]
    public function apply(Client $client, CheckLoanEligibilityRequestDto $loan): LoanRuleResult
    {
        if ($client->getRegion() !== 'PR') {
            return new LoanRuleResult(true);
        }

        return random_int(0, 1)
            ? new LoanRuleResult(true)
            : new LoanRuleResult(false, 'Random refusal for Prague');
    }
}

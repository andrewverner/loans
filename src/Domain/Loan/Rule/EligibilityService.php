<?php

namespace App\Domain\Loan\Rule;

use App\Application\Loan\CheckEligibility\CheckLoanEligibilityRequestDto;
use App\Domain\Client\Entity\Client;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class EligibilityService
{
    public function __construct(
        /** @var iterable<LoanRuleInterface> */
        #[TaggedIterator('app.loan_rule')]private readonly iterable $rules
    )
    {
    }

    public function check(Client $client, CheckLoanEligibilityRequestDto $loan): EligibilityResult
    {
        $modifier = 0.0;
        foreach ($this->rules as $rule) {
            $result = $rule->apply($client, $loan);
            if (!$result->passed) {
                return new EligibilityResult(false, $result->message);
            }
            $modifier += $result->rateModifier;
        }

        return new EligibilityResult(true, rateModifier: $modifier);
    }
}

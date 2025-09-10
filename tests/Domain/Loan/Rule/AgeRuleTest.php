<?php

namespace App\Tests\Domain\Loan\Rule;

use App\Domain\Client\Client;
use App\Domain\Loan\LoanRequest;
use App\Domain\Loan\Rule\AgeRule;
use PHPUnit\Framework\TestCase;

class AgeRuleTest extends TestCase
{
    public function test_passes_when_age_allowed(): void
    {
        $client = new Client('John', 25, 'PR', 2000, 600, '1', 'a@b.com', '123');
        $loan = new LoanRequest('Loan', 1000, 10, new \DateTimeImmutable(), new \DateTimeImmutable());
        $rule = new AgeRule();
        $this->assertTrue($rule->apply($client, $loan)->passed);
    }

    public function test_fails_when_age_not_allowed(): void
    {
        $client = new Client('John', 70, 'PR', 2000, 600, '1', 'a@b.com', '123');
        $loan = new LoanRequest('Loan', 1000, 10, new \DateTimeImmutable(), new \DateTimeImmutable());
        $rule = new AgeRule();
        $result = $rule->apply($client, $loan);
        $this->assertFalse($result->passed);
        $this->assertSame('Age not allowed', $result->message);
    }
}

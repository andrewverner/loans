<?php

namespace App\Tests\Domain\Loan\Rule;

use App\Domain\Client\Client;
use App\Domain\Loan\LoanRequest;
use App\Domain\Loan\Rule\LoanRuleResult;
use App\Domain\Loan\Rule\ScoreRule;
use PHPUnit\Framework\TestCase;

class ScoreRuleTest extends TestCase
{
    public function test_passes_when_score_high_enough(): void
    {
        $client = new Client('John', 30, 'PR', 2000, 700, '1', 'a@b.com', '123');
        $loan = new LoanRequest('Loan', 1000, 10, new \DateTimeImmutable(), new \DateTimeImmutable());
        $rule = new ScoreRule();
        $result = $rule->apply($client, $loan);
        $this->assertTrue($result->passed);
    }

    public function test_fails_when_score_low(): void
    {
        $client = new Client('John', 30, 'PR', 2000, 400, '1', 'a@b.com', '123');
        $loan = new LoanRequest('Loan', 1000, 10, new \DateTimeImmutable(), new \DateTimeImmutable());
        $rule = new ScoreRule();
        $result = $rule->apply($client, $loan);
        $this->assertFalse($result->passed);
        $this->assertSame('Score too low', $result->message);
    }
}

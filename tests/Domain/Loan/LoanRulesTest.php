<?php

namespace App\Tests\Domain\Loan\Rule;

use App\Application\Loan\CheckEligibility\CheckLoanEligibilityRequestDto;
use App\Domain\Client\Entity\Client;
use App\Domain\Loan\Rule\AgeRule;
use App\Domain\Loan\Rule\IncomeRule;
use App\Domain\Loan\Rule\OstravaRateIncreaseRule;
use App\Domain\Loan\Rule\PragueRandomRefusalRule;
use App\Domain\Loan\Rule\RegionRule;
use App\Domain\Loan\Rule\ScoreRule;
use PHPUnit\Framework\TestCase;

class LoanRulesTest extends TestCase
{
    private function createLoanRequest(): CheckLoanEligibilityRequestDto
    {
        return new CheckLoanEligibilityRequestDto('Test Loan', 1000, 10, '2025-01-01', '2026-01-01');
    }

    public function testAgeRule(): void
    {
        $rule = new AgeRule();
        $loan = $this->createLoanRequest();

        $client = (new Client())->setAge(25);
        $this->assertTrue($rule->apply($client, $loan)->passed);

        $client->setAge(65);
        $this->assertFalse($rule->apply($client, $loan)->passed);
    }

    public function testIncomeRule(): void
    {
        $rule = new IncomeRule();
        $loan = $this->createLoanRequest();

        $client = (new Client())->setIncome(1500);
        $this->assertTrue($rule->apply($client, $loan)->passed);

        $client->setIncome(500);
        $this->assertFalse($rule->apply($client, $loan)->passed);
    }

    public function testScoreRule(): void
    {
        $rule = new ScoreRule();
        $loan = $this->createLoanRequest();

        $client = (new Client())->setScore(600);
        $this->assertTrue($rule->apply($client, $loan)->passed);

        $client->setScore(300);
        $this->assertFalse($rule->apply($client, $loan)->passed);
    }

    public function testRegionRule(): void
    {
        $rule = new RegionRule();
        $loan = $this->createLoanRequest();

        $client = (new Client())->setRegion('PR');
        $this->assertTrue($rule->apply($client, $loan)->passed);

        $client->setRegion('XX');
        $this->assertFalse($rule->apply($client, $loan)->passed);
    }

    public function testOstravaRateIncreaseRule(): void
    {
        $rule = new OstravaRateIncreaseRule();
        $loan = $this->createLoanRequest();

        $client = (new Client())->setRegion('OS');
        $result = $rule->apply($client, $loan);
        $this->assertTrue($result->passed);
        $this->assertSame(5.0, $result->rateModifier);

        $client->setRegion('BR');
        $result = $rule->apply($client, $loan);
        $this->assertTrue($result->passed);
        $this->assertSame(0.0, $result->rateModifier);
    }

    public function testPragueRandomRefusalRuleNonPrague(): void
    {
        $rule = new PragueRandomRefusalRule();
        $loan = $this->createLoanRequest();

        $client = (new Client())->setRegion('BR');
        $this->assertTrue($rule->apply($client, $loan)->passed);
    }
}

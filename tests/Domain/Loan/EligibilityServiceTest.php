<?php

namespace App\Tests\Domain\Loan\Rule;

use App\Application\Loan\CheckEligibility\CheckLoanEligibilityRequestDto;
use App\Domain\Client\Entity\Client;
use App\Domain\Loan\Rule\AgeRule;
use App\Domain\Loan\Rule\EligibilityService;
use App\Domain\Loan\Rule\IncomeRule;
use App\Domain\Loan\Rule\OstravaRateIncreaseRule;
use App\Domain\Loan\Rule\PragueRandomRefusalRule;
use App\Domain\Loan\Rule\RegionRule;
use App\Domain\Loan\Rule\ScoreRule;
use PHPUnit\Framework\TestCase;

class EligibilityServiceTest extends TestCase
{
    private function createLoanRequest(): CheckLoanEligibilityRequestDto
    {
        return new CheckLoanEligibilityRequestDto('Test Loan', 1000, 10, '2025-01-01', '2026-01-01');
    }

    private function createValidClient(): Client
    {
        return (new Client())
            ->setAge(30)
            ->setIncome(2000)
            ->setScore(700)
            ->setRegion('OS');
    }

    public function testServiceApprovesAndAggregatesRateModifier(): void
    {
        $service = new EligibilityService([
            new AgeRule(),
            new IncomeRule(),
            new ScoreRule(),
            new RegionRule(),
            new OstravaRateIncreaseRule(),
            new PragueRandomRefusalRule(),
        ]);

        $client = $this->createValidClient();
        $loan = $this->createLoanRequest();

        $result = $service->check($client, $loan);

        $this->assertTrue($result->approved);
        $this->assertSame(5.0, $result->rateModifier);
    }

    public function testServiceStopsOnFailedRule(): void
    {
        $service = new EligibilityService([
            new AgeRule(),
            new IncomeRule(),
        ]);

        $client = (new Client())
            ->setAge(17)
            ->setIncome(2000);
        $loan = $this->createLoanRequest();

        $result = $service->check($client, $loan);

        $this->assertFalse($result->approved);
        $this->assertSame('Age not allowed', $result->reason);
    }
}

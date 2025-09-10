<?php

namespace App\Tests\Domain\Client;

use App\Domain\Client\Entity\Client;
use App\Domain\Loan\Entity\Loan;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ClientEntityTest extends TestCase
{
    private function createClient(): Client
    {
        return (new Client())
            ->setName('John Doe')
            ->setAge(30)
            ->setRegion('BR')
            ->setIncome(2000)
            ->setScore(700)
            ->setPin(123456)
            ->setEmail('john@example.com')
            ->setPhone('123456789012');
    }

    private function createLoan(): Loan
    {
        return (new Loan())
            ->setName('Test Loan')
            ->setAmount(1000)
            ->setRate(10)
            ->setStartDate(new DateTimeImmutable('2025-01-01'))
            ->setEndDate(new DateTimeImmutable('2026-01-01'));
    }

    public function testAddLoanSetsBackReference(): void
    {
        $client = $this->createClient();
        $loan = $this->createLoan();

        $client->addLoan($loan);

        $this->assertSame($client, $loan->getClient());
        $this->assertTrue($client->getLoans()->contains($loan));
    }

    public function testRemoveLoanClearsBackReference(): void
    {
        $client = $this->createClient();
        $loan = $this->createLoan();
        $client->addLoan($loan);

        $client->removeLoan($loan);

        $this->assertNull($loan->getClient());
        $this->assertFalse($client->getLoans()->contains($loan));
    }
}

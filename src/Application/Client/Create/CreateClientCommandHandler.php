<?php

declare(strict_types=1);

namespace App\Application\Client\Create;

use App\Domain\Client\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateClientCommandHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(CreateClientCommand $command): Client
    {
        $client = new Client();
        $client->setname($command->name);
        $client->setage($command->age);
        $client->setregion($command->region);
        $client->setincome($command->income);
        $client->setscore($command->score);
        $client->setpin($command->pin);
        $client->setemail($command->email);
        $client->setphone($command->phone);

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return $client;
    }
}

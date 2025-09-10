<?php

namespace App\Infrastructure\Client\Repository;

use App\Domain\Client\ClientRepositoryInterface;
use App\Domain\Client\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository implements ClientRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function save(Client $client): void
    {
    }

    public function findByPin(string $pin): ?Client
    {
        return $this->findOneBy(['pin' => $pin]);
    }
}

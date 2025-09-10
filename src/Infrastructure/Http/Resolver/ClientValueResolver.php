<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Resolver;

use App\Domain\Client\Entity\Client;
use App\Domain\Client\ClientRepositoryInterface;
use App\Infrastructure\Http\Attribute\MapClient;
use Override;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class ClientValueResolver implements ValueResolverInterface
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository
    ) {
    }

    #[Override]
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $attributes = $argument->getAttributes(MapClient::class, ArgumentMetadata::IS_INSTANCEOF);

        if ($attributes === []) {
            return [];
        }

        /** @var MapClient $mapClient */
        $mapClient = $attributes[0];

        $pin = $request->attributes->get('clientPin');
        if ($pin === null) {
            return [];
        }

        $client = $this->clientRepository->findByPin($pin);

        if (!$client instanceof Client) {
            throw new NotFoundHttpException("Client with {$mapClient->field}=$pin not found");
        }

        return [$client];
    }
}

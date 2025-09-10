<?php

declare(strict_types=1);

namespace App\Application\Client\Create;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    schema: "CreateClientRequestDto",
    description: "Запрос на создание клиента",
    required: ["name", "age", "region", "income", "email", "phone"]
)]
final class CreateClientRequestDto
{
    public function __construct(
        #[OA\Property(
            description: "Имя клиента",
            type: "string",
            maxLength: 255,
            minLength: 3,
            example: "Иван Иванов"
        )]
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Length(min: 3, max: 255)]
        public string $name,
        #[OA\Property(
            description: "Возраст клиента (не младше 18)",
            type: "integer",
            minimum: 18,
            example: 25
        )]
        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        #[Assert\GreaterThanOrEqual(18)]
        public int $age,
        #[OA\Property(
            description: "Код региона (2 буквы из списка RegionEnum)",
            type: "string",
            maxLength: 2,
            minLength: 2,
            example: "PR"
        )]
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Length(min: 2, max: 2)]
        //#[Assert\Choice(callback: [RegionEnum::class, 'cases'])]
        public string $region,
        #[OA\Property(
            description: "Доход клиента",
            type: "integer",
            minimum: 0,
            example: 50000
        )]
        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        #[Assert\PositiveOrZero]
        public int $income,
        #[OA\Property(
            description: "Скорринг (необязательное поле)",
            type: "integer",
            minimum: 0,
            example: 10,
            nullable: true
        )]
        #[Assert\Type('integer')]
        #[Assert\PositiveOrZero]
        public ?int $score,
        #[OA\Property(
            description: "PIN клиента (необязательное поле, >0)",
            type: "integer",
            minimum: 1,
            example: 1234,
            nullable: true
        )]
        #[Assert\Type('integer')]
        #[Assert\Positive]
        public ?int $pin,
        #[OA\Property(
            description: "Email клиента",
            type: "string",
            format: "email",
            example: "user@example.com"
        )]
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,
        #[OA\Property(
            description: "Телефон клиента в формате +79991234567",
            type: "string",
            pattern: "^\+[\d]{11}$",
            example: "+79991234567"
        )]
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Regex(pattern: '/^\+[\d]{11}$/')]
        public string $phone,
    ) {
        if ($this->pin === null) {
            $this->pin = mt_rand(0, 999999999);
        }

        if ($this->score === null) {
            $this->score = mt_rand(0, 1000);
        }
    }
}

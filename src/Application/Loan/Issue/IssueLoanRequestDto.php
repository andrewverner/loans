<?php

declare(strict_types=1);

namespace App\Application\Loan\Issue;

use DateTimeImmutable;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    schema: "IssueLoanRequestDto",
    description: "Запрос на выдачу кредита",
    required: ["name", "amount", "rate", "startDate", "endDate"]
)]
class IssueLoanRequestDto
{
    public function __construct(
        #[OA\Property(
            description: "Имя кредита",
            type: "string",
            maxLength: 255,
            minLength: 3,
            example: "Ипотека"
        )]
        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 255)]
        public string $name,
        #[OA\Property(
            description: "Сумма кредита",
            type: "integer",
            minimum: 0,
            example: 10000
        )]
        #[Assert\NotBlank]
        #[Assert\PositiveOrZero]
        public int $amount,
        #[OA\Property(
            description: "Процентная ставка",
            type: "integer",
            minimum: 0,
            example: 10
        )]
        #[Assert\NotBlank]
        #[Assert\PositiveOrZero]
        public int $rate,
        #[OA\Property(
            description: "Дата начала кредита",
            type: "string",
            format: "date",
            example: "2025-01-01"
        )]
        #[Assert\NotBlank]
        #[Assert\Regex(pattern: "/^[\d]{4}-[\d]{2}-[\d]{2}$/")]
        public string $startDate,
        #[OA\Property(
            description: "Дата окончания кредита",
            type: "string",
            format: "date",
            example: "2030-01-01"
        )]
        #[Assert\NotBlank]
        #[Assert\Regex(pattern: "/^[\d]{4}-[\d]{2}-[\d]{2}$/")]
        public string $endDate,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class MapClient
{
    public function __construct(
        public string $field = 'pin'
    ) {
    }
}

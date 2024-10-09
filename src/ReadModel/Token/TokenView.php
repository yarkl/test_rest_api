<?php

declare(strict_types=1);

namespace App\ReadModel\Token;

readonly class TokenView
{
    public function __construct(
        public string $uuid,
        public string $token,
    ){}
}

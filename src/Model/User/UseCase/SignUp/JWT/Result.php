<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\JWT;

readonly class Result
{
    public function __construct(
        public string $token,
        public string $refreshToken,
    ) {}
}

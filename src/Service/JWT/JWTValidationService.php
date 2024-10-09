<?php

declare(strict_types=1);

namespace App\Service\JWT;

use App\Model\Service\JwtServiceInterface;
use App\ReadModel\Token\TokenFetcher;

class JWTValidationService
{
    public function __construct(
        private readonly JwtServiceInterface $JWTService,
        private readonly TokenFetcher $tokenFetcher
    ) {}

    public function validate(string $token): bool
    {
        $tokenView = $this->tokenFetcher->findByToken($token);

        if (!$this->JWTService->validateToken($tokenView?->token)) {
            return false;
        }

        $token = $this->JWTService->decodeToken($tokenView?->token);

        return time() < $token['exp'];
    }
}

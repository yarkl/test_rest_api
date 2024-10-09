<?php

namespace App\Model\Service;

interface JwtServiceInterface
{

    public function decodeToken(string $token): mixed;

    public function createToken(array $payload): mixed;

    public function validateToken(string $token): bool;
}

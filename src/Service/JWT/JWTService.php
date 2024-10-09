<?php

declare(strict_types=1);

namespace App\Service\JWT;

use App\Model\Service\JWTServiceInterface;

class JWTService implements JWTServiceInterface
{

    public function __construct(
        private readonly string $secretKey,
        public readonly int $tokenTtl,
        public readonly int $refreshTokenTtl,
    ) {}

    public function createToken(array $payload): string
    {
        $base64UrlHeader = $this->base64UrlEncode(json_encode(["alg" => "HS256", "typ" => "JWT"]));
        $base64UrlPayload = $this->base64UrlEncode(json_encode($payload));
        $base64UrlSignature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $this->secretKey, true);
        $base64UrlSignature = $this->base64UrlEncode($base64UrlSignature);

        return $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
    }

    private function base64UrlEncode($data): string
    {
        $base64 = base64_encode($data);
        $base64Url = strtr($base64, '+/', '-_');

        return rtrim($base64Url, '=');
    }

    private function base64UrlDecode($data): string
    {
        $base64 = strtr($data, '-_', '+/');
        $base64Padded = str_pad($base64, strlen($base64) % 4, '=', STR_PAD_RIGHT);

        return base64_decode($base64Padded);
    }

    public function validateToken($token): bool
    {
        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode('.', $token);

        $signature = $this->base64UrlDecode($base64UrlSignature);
        $expectedSignature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $this->secretKey, true);

        return hash_equals($signature, $expectedSignature);
    }

    public function decodeToken(string $token): array
    {
        list(, $base64UrlPayload, ) = explode('.', $token);
        $payload = $this->base64UrlDecode($base64UrlPayload);

        return json_decode($payload, true);
    }
}

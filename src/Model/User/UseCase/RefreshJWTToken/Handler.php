<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\RefreshJWTToken;

use DomainException;
use App\Model\Flusher;
use App\Model\Service\JwtServiceInterface;
use App\Model\User\Entity\Token\Token;
use App\Model\User\Entity\Token\TokenRepository;

readonly class Handler
{
    public function __construct(
        private JwtServiceInterface $jwtService,
        private TokenRepository $tokenRepository,
        private Flusher $flusher
    ) {}

    public function handle(Command $command): Result
    {
        if (!$refreshToken = $this->tokenRepository->findByRefreshToken($command->refreshToken)) {
            throw new \DomainException("Refresh token not found!");
        }

        if (!$this->jwtService->validateToken($refreshToken->getToken())) {
            throw new DomainException('Token validation failed!');
        }

        $decodedToken = $this->jwtService->decodeToken($refreshToken->getToken());

        if (time() > $decodedToken['exp']) {
            throw new DomainException('Refresh token expired! Login again!');
        }

        $user = $refreshToken->getUser();

        $tokenPayload = [
            'user_name' => $user->getUserName(),
            'user_id' => $user->getUuid()->getValue(),
            'exp' => time() + $this->jwtService->tokenTtl,
        ];

        $refreshTokenPayload = [
            'user_name' => $user->getUserName(),
            'user_id' => $user->getUserName(),
            'exp' => time() + $this->jwtService->refreshTokenTtl,
        ];

        $token = new Token(
            $this->jwtService->createToken($tokenPayload),
            $this->jwtService->createToken($refreshTokenPayload),
            $user
        );

        $this->tokenRepository->remove($refreshToken);
        $this->tokenRepository->add($token);
        $this->flusher->flush();

        return new Result(
            $token->getToken(),
            $token->getRefreshToken()
        );
    }
}

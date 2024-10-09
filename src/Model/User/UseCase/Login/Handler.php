<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Login;

use App\Model\Flusher;
use App\Model\Service\JwtServiceInterface;
use App\Model\User\Entity\Token\Token;
use DomainException;
use App\Model\User\Entity\User\UserRepository;

readonly class Handler
{
    public function __construct(
        private UserRepository $userRepository,
        private JwtServiceInterface $jwtService,
        private Flusher $flusher,
    ) {}

    public function handle(Command $command): Result
    {
        if(!$user = $this->userRepository->findByName($command->userName)) {
            throw new DomainException('User not found');
        }

        $tokenPayload = [
            'user_name' => $command->userName,
            'user_id' => $user->getUuid()->getValue(),
            'exp' => time() + $this->jwtService->tokenTtl,
        ];

        $refreshTokenPayload = [
            'user_name' => $command->userName,
            'user_id' => $user->getUuid()->getValue(),
            'exp' => time() + $this->jwtService->refreshTokenTtl,
        ];

        $token = new Token(
            $this->jwtService->createToken($tokenPayload),
            $this->jwtService->createToken($refreshTokenPayload),
            $user
        );

        $user->addTokens($token);

        $this->userRepository->add($user);
        $this->flusher->flush();

        return new Result($token->getToken(), $token->getRefreshToken());
    }
}
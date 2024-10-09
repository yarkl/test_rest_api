<?php

declare(strict_types=1);

namespace App\ReadModel\Token;

use Doctrine\DBAL\Connection;

readonly class TokenFetcher
{
    public function __construct(
        private readonly Connection $connection
    ) {}

    public function findByToken(string $token): ?TokenView
    {
        try {
            $stmt = $this->connection->createQueryBuilder()
                ->select(
                    't.user_uuid',
                    't.token',
                )
                ->from('tokens t')
                ->where('token = :token')
                ->setParameter('token', $token)
                ->executeQuery();

            $result = $stmt->fetchAssociative();

            return new TokenView($result["user_uuid"], $result["token"]);
        } catch (\Throwable $exception) {
            //TODO log exception
            return null;
        }
    }
}

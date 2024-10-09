<?php

declare(strict_types=1);

namespace App\Model\User\Entity\Token;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class TokenRepository
{
    private EntityManagerInterface $em;

    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->repository = $entityManager->getRepository(Token::class);
    }

    public function findByToken(string $token): ?Token
    {
        return $this->repository->findOneBy(['token' => $token]);
    }

    public function findByRefreshToken(string $token): ?Token
    {
        return $this->repository->findOneBy(['refreshToken' => $token]);
    }

    public function add(Token $token): void
    {
        $this->em->persist($token);
    }

    public function remove(Token $token): void
    {
        $this->em->remove($token);
    }
}

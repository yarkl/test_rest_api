<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository
{
    private EntityManagerInterface $em;

    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->repository = $entityManager->getRepository(User::class);
    }

    public function findByName(string $uuid): ?User
    {
        return $this->repository->findOneBy(['userName' => $uuid]);
    }

    public function findByUuid(string $uuid): ?User
    {
        return $this->repository->findOneBy(['uuid' => $uuid]);
    }

    public function add(User $user): void
    {
        $this->em->persist($user);
    }
}

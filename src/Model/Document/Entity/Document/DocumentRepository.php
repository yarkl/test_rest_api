<?php

declare(strict_types=1);

namespace App\Model\Document\Entity\Document;

use App\Model\User\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DocumentRepository
{
    private EntityManagerInterface $em;

    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->repository = $entityManager->getRepository(Document::class);
    }

    public function findByUuid(string $uuid): ?Document
    {
        return $this->repository->findOneBy(['uuid' => $uuid]);
    }

    public function add(Document $document): void
    {
        $this->em->persist($document);
    }
}

<?php

declare(strict_types=1);

namespace App\Model\Document\UseCase\Create;

use App\Model\Document\Entity\Document\Document;
use App\Model\Document\Entity\Document\DocumentRepository;
use App\Model\Document\Entity\Document\Id;
use App\Model\Document\UseCase\Result;
use App\Model\Flusher;

class Handler
{
    private Flusher $flusher;

    private DocumentRepository $repository;

    public function __construct(
        DocumentRepository $documentRepository,
        Flusher $flusher
    ) {
        $this->flusher = $flusher;
        $this->repository = $documentRepository;
    }

    public function handle(Command $command): Result
    {
        $date = new \DateTime();

        $document = new Document(
            Id::next(),
            Document::STATUS_DRAFT,
            $command->payload,
            $date,
            $date
        );

        $this->repository->add($document);

        $this->flusher->flush($document);

        return new Result(
            $document->getUuid()->getValue(),
            $document->status(),
            json_decode($document->payload(), true),
            $document->getCreatedAt()->format('Y-m-d H:i:s'),
            $document->getModifiedAt()->format('Y-m-d H:i:s')
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Model\Document\UseCase\Update;

use App\Model\Document\DocumentNotFoundException;
use App\Model\Document\Entity\Document\DocumentRepository;
use App\Model\Document\UseCase\Result;
use App\Model\Flusher;

class Handler
{
    public function __construct(
        private readonly DocumentRepository $repository,
        private readonly Flusher $flusher
    ) {}

    public function handle(Command $command): Result
    {
        $document = $this->repository->findByUuid($command->uuid);

        if (!$document) {
            throw new DocumentNotFoundException("Document with uuid '{$command->uuid}' not found");
        }

        $document->updatePayload($command->payload);
        $document->modifiedAt();

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

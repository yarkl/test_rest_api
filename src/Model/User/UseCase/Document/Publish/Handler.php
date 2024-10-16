<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Document\Publish;

use App\Model\DocumentNotFoundException;
use App\Model\Flusher;
use App\Model\User\Entity\Document\DocumentRepository;
use App\Model\User\UseCase\Document\Result;

readonly class Handler
{
    public function __construct(
        private DocumentRepository $repository,
        private Flusher $flusher
    ) {}

    public function handle(Command $command): Result
    {
        $document = $this->repository->findByUuid($command->uuid);

        if (!$document) {
            throw new DocumentNotFoundException("Document with uuid '{$command->uuid}' not found");
        }

        $document->updatePayload($command->payload);
        $document->modifiedAt();
        $document->publish();

        $this->flusher->flush($document);

        return new Result(
            $document->getUuid()->getValue(),
            $document->status(),
            $command->payload,
            $document->getCreatedAt()->format('Y-m-d H:i:s'),
            $document->getModifiedAt()->format('Y-m-d H:i:s')
        );
    }
}

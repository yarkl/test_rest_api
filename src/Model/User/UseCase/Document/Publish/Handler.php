<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Document\Publish;

use App\Model\DocumentNotFoundException;
use App\Model\Flusher;
use App\Model\User\Entity\Document\DocumentRepository;
use App\Model\User\UseCase\Document\Result;

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

        $payload = json_decode($command->payload, true);

        $document->updatePayload($payload);
        $document->modifiedAt();
        $document->publish();

        $this->flusher->flush($document);

        return new Result(
            $document->getUuid()->getValue(),
            $document->status(),
            $payload,
            $document->getCreatedAt()->format('Y-m-d H:i:s'),
            $document->getModifiedAt()->format('Y-m-d H:i:s')
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Document;

class Result
{
    public function __construct(
        public string $id,
        public string $status,
        public array $payload,
        public string $createdAt,
        public string $modifiedAt
    ){}

    public function toArray(): array
    {
        return [
            'document' => [
                'id' => $this->id,
                'status' => $this->status,
                'payload' => $this->payload,
                'createAt' => $this->createdAt,
                'modifyAt' => $this->modifiedAt,
            ]
        ];
    }
}
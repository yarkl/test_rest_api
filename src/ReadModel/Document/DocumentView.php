<?php

declare(strict_types=1);

namespace App\ReadModel\Document;

readonly class DocumentView
{
    public function __construct(
        public string $uuid,
        public string $status,
        public array $payload,
        public string $createAt,
        public string $modifyAt,
    ){}
}
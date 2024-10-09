<?php

declare(strict_types=1);

namespace App\ReadModel\Document;

readonly class AllDocumentsView
{
    public function __construct(
        public array $pagination,
        /** DocumentView [] */
        public array $documents,
    ) {}
}

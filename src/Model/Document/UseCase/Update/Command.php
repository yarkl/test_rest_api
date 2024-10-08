<?php

declare(strict_types=1);

namespace App\Model\Document\UseCase\Update;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    public function __construct(
        #[Assert\Uuid(strict: false)]
        public string $uuid,
        #[Assert\Json]
        public string $payload,
    ){}
}

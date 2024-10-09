<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Document\Update;

use Symfony\Component\Validator\Constraints as Assert;

readonly class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $token,
        #[Assert\Uuid(strict: false)]
        public string $uuid,
        #[Assert\Json]
        public string $payload,
    ){}
}

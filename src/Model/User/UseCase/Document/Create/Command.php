<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Document\Create;

use Symfony\Component\Validator\Constraints as Assert;

readonly class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $token,
        #[Assert\Json]
        public string $payload
    ) {}
}

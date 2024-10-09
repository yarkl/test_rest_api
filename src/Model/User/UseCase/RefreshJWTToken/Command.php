<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\RefreshJWTToken;

use Symfony\Component\Validator\Constraints as Assert;

readonly class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $refreshToken,
    ) {}
}

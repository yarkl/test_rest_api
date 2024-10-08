<?php

declare(strict_types=1);

namespace App\Model\Document\Entity\Document;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class Id
{
    public function __construct(
        #[Assert\NotBlank]
        private readonly string $value
    ) {}

    public static function next(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
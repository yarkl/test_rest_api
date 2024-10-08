<?php

declare(strict_types=1);

namespace App\Model\Document\Entity\Document;

use App\Model\AggregateRoot;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "document")]
class Document implements AggregateRoot
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    #[ORM\Column(type: 'document_document_id')]
    #[ORM\Id]
    private Id|string $uuid;

    #[ORM\Column(length: 255)]
    private string $status;

    #[ORM\Column(type: 'json')]
    private string $payload;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime')]
    private DateTime $modifiedAt;

    public function __construct(
        Id $uuid,
        string $status,
        string $payload,
        DateTime $createdAt,
        DateTime $modifiedAt
    ) {
        $this->uuid = $uuid;
        $this->status = $status;
        $this->payload = $payload;
        $this->createdAt = $createdAt;
        $this->modifiedAt = $modifiedAt;
    }

    public function releaseEvents(): array
    {
        return [];
    }

    public function getUuid(): Id
    {
        return $this->uuid;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function payload(): string
    {
        return $this->payload;
    }

    public function updatePayload(string $payload): void
    {
        $this->payload = $payload;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getModifiedAt(): DateTime
    {
        return $this->modifiedAt;
    }

    public function modifiedAt(): void
    {
        $this->modifiedAt = new DateTime();
    }

    public function publish(): void
    {
        $this->status = self::STATUS_PUBLISHED;
    }
}

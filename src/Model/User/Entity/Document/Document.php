<?php

declare(strict_types=1);

namespace App\Model\User\Entity\Document;

use App\Model\AggregateRoot;
use App\Model\User\Entity\Common\Id;
use App\Model\User\Entity\User\User;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "document")]
class Document implements AggregateRoot
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    #[ORM\Column(type: 'custom_uuid')]
    #[ORM\Id]
    private Id|string $uuid;

    #[ORM\Column(length: 255)]
    private string $status;

    #[ORM\Column(type: 'json')]
    private array|string $payload;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime')]
    private DateTime $modifiedAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'documents')]
    #[ORM\JoinColumn(name: 'user_uuid', referencedColumnName: 'uuid')]
    private User $user;

    public function __construct(
        Id $uuid,
        string $status,
        array|string $payload,
        DateTime $createdAt,
        DateTime $modifiedAt,
        User $user
    ) {
        $this->uuid = $uuid;
        $this->status = $status;
        $this->payload = $payload;
        $this->createdAt = $createdAt;
        $this->modifiedAt = $modifiedAt;
        $this->user = $user;
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

    public function payload(): array|string
    {
        return $this->payload;
    }

    public function updatePayload(array|string $payload): void
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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use App\Model\AggregateRoot;
use App\Model\User\Entity\Common\Id;
use App\Model\User\Entity\Document\Document;
use App\Model\User\Entity\Token\Token;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "user")]
class User implements AggregateRoot
{
    #[ORM\Column(type: 'custom_uuid')]
    #[ORM\Id]
    private Id $uuid;

    #[ORM\Column(length: 255, unique: true)]
    private string $userName;

    #[ORM\OneToMany(targetEntity: Token::class, mappedBy: 'user_uuid', cascade: ["persist"], orphanRemoval: true)]
    private $tokens;

    #[ORM\OneToMany(targetEntity: Document::class, mappedBy: 'user_uuid', cascade: ["persist"], orphanRemoval: true)]
    private $documents;

    public function __construct(
        Id $uuid,
        string $userName,
    ) {
        $this->uuid = $uuid;
        $this->userName = $userName;
        $this->tokens = new ArrayCollection();
        $this->documents = new ArrayCollection();
    }

    public function getUuid(): Id
    {
        return $this->uuid;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function addTokens(Token ...$tokens): self
    {
        $this->tokens->add(...$tokens);

        return $this;
    }

    public function getTokens(): ArrayCollection
    {
        return $this->tokens;
    }

    public function removeToken(Token $token): self
    {
        $this->tokens->removeElement($token);

        return $this;
    }

    public function addDocuments(Document ...$documents): self
    {
        $this->tokens->add(...$documents);

        return $this;
    }

    public function releaseEvents(): array
    {
        return [];
    }
}

<?php

declare(strict_types=1);

namespace App\Model\User\Entity\Token;

use App\Model\User\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "tokens")]
class Token
{
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tokens')]
    #[ORM\JoinColumn(name: 'user_uuid', referencedColumnName: 'uuid')]
    private User $user;

    #[ORM\Column(length: 255, unique: true)]
    #[ORM\Id]
    private string $token;

    #[ORM\Column(length: 255, unique: true)]
    private string $refreshToken;

    public function __construct(
        string $token,
        string $refreshToken,
        User $user
    ) {
        $this->token = $token;
        $this->refreshToken = $refreshToken;
        $this->user = $user;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}

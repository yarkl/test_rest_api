<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Document\Create;

use App\Model\Flusher;
use App\Model\Service\JwtServiceInterface;
use App\Model\User\Entity\Common\Id;
use App\Model\User\Entity\Document\Document;
use App\Model\User\Entity\Document\DocumentRepository;
use App\Model\User\Entity\Token\TokenRepository;
use App\Model\User\UseCase\Document\Result;
use DomainException;

class Handler
{
    public function __construct(
        private readonly DocumentRepository $documentRepository,
        private readonly TokenRepository  $tokenRepository,
        private readonly JwtServiceInterface $JWTService,
        private readonly Flusher $flusher
    ) {}

    public function handle(Command $command): Result
    {
        if (!$token = $this->tokenRepository->findByToken($command->token)) {
            throw new DomainException('Token not found!');
        }

        if (!$this->JWTService->validateToken($token->getToken())) {
            throw new DomainException('Token validation failed!');
        }

        $decodedToken = $this->JWTService->decodeToken($token->getToken());

        if (time() > $decodedToken['exp']) {
            throw new DomainException('Token has been expired!');
        }
        $user = $token->getUser();
        $payload = json_decode($command->payload, true);

        $document = new Document(
            Id::next(),
            Document::STATUS_DRAFT,
            $payload,
            $date = new \DateTime(),
            $date,
            $user
        );

        $this->documentRepository->add($document);
        $this->flusher->flush($document);

        return new Result(
            $document->getUuid()->getValue(),
            $document->status(),
            $payload,
            $document->getCreatedAt()->format('Y-m-d H:i:s'),
            $document->getModifiedAt()->format('Y-m-d H:i:s')
        );
    }
}

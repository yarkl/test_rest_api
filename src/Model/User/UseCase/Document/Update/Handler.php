<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Document\Update;

use App\Model\DocumentNotFoundException;
use App\Model\Flusher;
use App\Model\Service\JwtServiceInterface;
use App\Model\User\Entity\Document\DocumentRepository;
use App\Model\User\Entity\Token\TokenRepository;
use App\Model\User\UseCase\Document\Result;
use App\Service\JWT\JWTService;
use DomainException;

class Handler
{
    public function __construct(
        private readonly DocumentRepository $repository,
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

        $document = $this->repository->findByUuid($command->uuid);

        if (!$document) {
            throw new DocumentNotFoundException("Document with uuid '{$command->uuid}' not found");
        }

        if (time() > $decodedToken['exp'] || $decodedToken['user_id'] != $document->getUser()->getUuid()->getValue()) {
            throw new DomainException('Invalid token!');
        }

        $payload = json_decode($command->payload, true);
        $document->updatePayload($payload);
        $document->modifiedAt();

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

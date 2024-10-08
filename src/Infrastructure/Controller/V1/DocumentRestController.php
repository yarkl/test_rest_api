<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\V1;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;
use DomainException;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Document\UseCase\Create;
use App\Model\Document\UseCase\Update;
use App\Model\Document\UseCase\Publish;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DocumentRestController extends AbstractController
{

    #[Route('/api/v1/document', name: 'document_create', methods: ['POST'], format: "json")]
    public function create(
        Request $request,
        ValidatorInterface $validator,
        Create\Handler $handler
    ): JsonResponse {
        $command = new Create\Command($request->getContent());

        $errors = $validator->validate($command);

        if (count($errors) > 0) {
            return new JsonResponse([], 400);
        }

        try {
            $result = $handler->handle($command);

            return new JsonResponse($result->toArray(),  201);
        } catch (DomainException $e) {
            return new JsonResponse("Error");
        }
    }

    #[Route('/api/v1/document/{uuid}', name: 'document_update', methods: ['PATCH'], format: "json")]
    public function update(
        string $uuid,
        Request $request,
        ValidatorInterface $validator,
        Update\Handler $handler
    ): JsonResponse {
        $command = new Update\Command($uuid, $request->getContent());

        $errors = $validator->validate($command);

        if (count($errors) > 0) {
            return new JsonResponse([], 400);
        }

        try {
            $result = $handler->handle($command);

            return new JsonResponse($result->toArray(),  201);
        } catch (DomainException $e) {
            return new JsonResponse($e->getMessage());
        }
    }

    #[Route('/api/v1/document/{uuid}/publish', name: 'document_publish', methods: ['POST'], format: "json")]
    public function publish(
        string $uuid,
        Request $request,
        ValidatorInterface $validator,
        Publish\Handler $handler
    ): JsonResponse {
        $command = new  Publish\Command($uuid, $request->getContent());

        $errors = $validator->validate($command);

        if (count($errors) > 0) {
            return new JsonResponse([], 400);
        }

        try {
            $result = $handler->handle($command);

            return new JsonResponse($result->toArray(),  201);
        } catch (DomainException $e) {
            return new JsonResponse($e->getMessage());
        }
    }
}

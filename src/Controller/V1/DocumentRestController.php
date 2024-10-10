<?php

declare(strict_types=1);

namespace App\Controller\V1;

use App\Model\User\UseCase\Document\Create;
use App\Model\User\UseCase\Document\Publish;
use App\Model\User\UseCase\Document\Update;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Security\Attribute\SecuredEndpoint;

#[SecuredEndpoint]
class DocumentRestController extends AbstractController
{

    #[Route('/api/v1/document', name: 'document_create', methods: ['POST'], format: "json")]
    public function create(
        Request $request,
        ValidatorInterface $validator,
        Create\Handler $handler,
    ): JsonResponse {
        try {
            $command = new Create\Command(
                $request->get('authorization_token'),
                json_decode($request->getContent(), true)['payload']
            );
        } catch (\Throwable $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], 400);
        }

        $errors = $validator->validate($command);

        if (count($errors) > 0) {
            return new JsonResponse([], 400);
        }

        try {
            $result = $handler->handle($command);

            return new JsonResponse($result->toArray(),  201);
        } catch (DomainException $e) {
            return new JsonResponse("Error", 400);
        }
    }

    #[Route('/api/v1/document/{uuid}', name: 'document_update', methods: ['PATCH'], format: "json")]
    public function update(
        string $uuid,
        Request $request,
        ValidatorInterface $validator,
        Update\Handler $handler
    ): JsonResponse {
        try {
            $command = new Update\Command(
                $request->get('authorization_token'),
                $uuid,
                json_decode($request->getContent(), true)['payload']
            );

        } catch (\Throwable $exception) {
            return new JsonResponse($exception->getMessage(), 400);
        }

        $errors = $validator->validate($command);

        if (count($errors) > 0) {
            return new JsonResponse([], 400);
        }

        try {
            $result = $handler->handle($command);

            return new JsonResponse($result->toArray(),  201);
        } catch (DomainException $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }

    #[Route('/api/v1/document/{uuid}/publish', name: 'document_publish', methods: ['POST'], format: "json")]
    public function publish(
        string $uuid,
        Request $request,
        ValidatorInterface $validator,
        Publish\Handler $handler
    ): JsonResponse {
        try {
            $command = new  Publish\Command(
                $request->get('authorization_token'),
                $uuid,
                $request->getContent()['payload']
            );

        } catch (\Throwable $exception) {
            return new JsonResponse($exception->getMessage(), 400);
        }

        $errors = $validator->validate($command);

        if (count($errors) > 0) {
            return new JsonResponse([], 400);
        }

        try {
            $result = $handler->handle($command);

            return new JsonResponse($result->toArray(),  201);
        } catch (DomainException $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }
}

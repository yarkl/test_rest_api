<?php

declare(strict_types=1);

namespace App\Controller\V1;

use DomainException;
use App\Model\User\UseCase\RefreshJWTToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RefreshTokenController extends AbstractController
{
    #[Route('/api/v1/refresh-token', name: 'refresh', methods: ['POST'], format: "json")]
    public function refresh(
        Request $request,
        ValidatorInterface $validator,
        RefreshJWTToken\Handler $handler,
    ): JsonResponse {
        if (!json_validate($request->getContent())) {
            throw new DomainException('Invalid JSON!');
        }

        $content = json_decode($request->getContent(), true);

        $command = new RefreshJWTToken\Command($content['refresh_token']);

        $errors = $validator->validate($command);

        if (count($errors) > 0) {
            return new JsonResponse([], 400);
        }

        try {
            $result = $handler->handle($command);

            return new JsonResponse($result,  201);
        } catch (DomainException $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }

}
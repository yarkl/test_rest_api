<?php

declare(strict_types=1);

namespace App\Controller\V1;

use App\Model\User\UseCase\SignUp\JWT;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SignUpController extends AbstractController
{

    #[Route('/api/v1/signup', name: 'signup', methods: ['POST'], format: "json")]
    public function singUp(
        Request $request,
        ValidatorInterface $validator,
        JWT\Handler $handler,
    ): JsonResponse {
        if (!json_validate($request->getContent())) {
            throw new DomainException('Invalid JSON');
        }

        $content = json_decode($request->getContent(), true);

        $command = new JWT\Command($content['login']);

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

<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ErrorController
{
    #[Route('/error', name: 'error', methods: ['GET'], format: "json")]
    public function create(): JsonResponse
    {
        return new JsonResponse([
            'status' => 'error',
            'message' => 'Invalid token or token has been expired!'
        ], 401);
    }
}

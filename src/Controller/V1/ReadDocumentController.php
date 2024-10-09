<?php

declare(strict_types=1);

namespace App\Controller\V1;

use App\ReadModel\Document\DocumentFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ReadDocumentController extends AbstractController
{
    public function __construct(
        private DocumentFetcher $fetcher
    ) {}

    #[Route('/api/v1/document/{uuid}', name: 'get_document', methods: ['GET'])]
    public function getDocument(
        string $uuid,
    ): JsonResponse {
        if(!$result = $this->fetcher->findByUuid($uuid)) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Document not found',
            ], 404);
        }

        return new JsonResponse([
            'document' => $result,
        ], 200);
    }

    #[Route('/api/v1/document/', name: 'get_all_document', methods: ['GET'])]
    public function getAll(Request $request): JsonResponse {
        $result = $this->fetcher->all(
            (int)$request->get('page', 1),
            (int)$request->get('perPage', 10)
        );

        return new JsonResponse($result, 200);
    }
}
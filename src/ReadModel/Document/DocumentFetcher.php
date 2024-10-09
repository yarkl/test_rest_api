<?php

declare(strict_types=1);

namespace App\ReadModel\Document;

use App\ReadModel\Token\TokenView;
use Doctrine\DBAL\Connection;
use Knp\Component\Pager\PaginatorInterface;

readonly class DocumentFetcher
{
    public function __construct(
        private  Connection $connection,
        private  PaginatorInterface $paginator
    ) {}

    public function findByUuid(string $uuid): ?DocumentView
    {
        try {
            $stmt = $this->connection->createQueryBuilder()
                ->select(
                    'd.uuid',
                    'd.status',
                    'd.payload',
                    'd.created_at',
                    'd.modified_at',
                )
                ->from('document d')
                ->where('uuid = :uuid')
                ->setParameter('uuid', $uuid)
                ->executeQuery();

            $result = $stmt->fetchAssociative();

            return new DocumentView(
                $result["uuid"],
                $result["status"],
                json_decode($result["payload"], true),
                $result["created_at"],
                $result["modified_at"]
            );
        } catch (\Throwable $exception) {
            //TODO log exception
            return null;
        }
    }

    public function all(int $page, int $perPage): ?AllDocumentsView
    {
        try {
            $qb = $this->connection->createQueryBuilder()
                ->select(
                    'd.*',
                )
                ->from('document d')
                ->orderBy("d.created_at", "DESC");

            $result = $this->paginator->paginate($qb, $page, $perPage);

            $items = [];
            foreach ($result as $document) {
                $items[] = new DocumentView(
                    $document["uuid"],
                    $document["status"],
                    json_decode($document["payload"], true),
                    $document["created_at"],
                    $document["modified_at"]
                );
            }

            return new AllDocumentsView([
                'page' => $page,
                'perPage' => $perPage,
                'total' => count($items),
            ], $items);
        } catch (\Throwable $exception) {
            //TODO log exception
            return null;
        }
    }
}
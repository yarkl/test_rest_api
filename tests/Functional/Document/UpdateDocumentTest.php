<?php

declare(strict_types=1);

namespace App\Test\Functional\Document;

use App\Test\Functional\DbWebTestCase;

class UpdateDocumentTest extends DbWebTestCase
{
    private const URI = '/api/v1/document';

    public function testUpdate(): void
    {
        $this->client->request(
            'POST',
            self::URI,
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'first_name' => 'John',
                'last_name' => 'Doe',
            ])
        );
        $content = json_decode($this->client->getResponse()->getContent(), true);

        $updatedDocument = [
            'first_name' => 'Alexandr',
            'last_name' => 'Doe',
        ];

        $this->client->request(
            'PATCH',
            self::URI . '/'. $content['document']['id'],
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($updatedDocument)
        );

        self::assertEquals(201, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());

        $content = json_decode($content, true);

        self::assertEquals($updatedDocument, $content['document']['payload']);
    }
}

<?php

declare(strict_types=1);

namespace App\Test\Functional\Document;

use App\Test\Functional\DbWebTestCase;

class CreateDocumentTest extends DbWebTestCase
{
    private const URI = '/api/v1/document';

    public function testCreate(): void
    {
        $payload = [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ];

        $this->client->request(
            'POST',
            self::URI,
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($payload)
        );

        self::assertEquals(201, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());

        $content = json_decode($content, true);

        self::assertEquals($payload, $content['document']['payload']);
    }
}

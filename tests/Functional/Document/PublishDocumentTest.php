<?php

declare(strict_types=1);

namespace App\Test\Functional\Document;

use App\Test\Functional\DbWebTestCase;

class PublishDocumentTest extends DbWebTestCase
{
    private const URI = '/api/v1/document';

    public function testPublish(): void
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

        $this->client->request(
            'POST',
            self::URI . '/'. $content['document']['id'] . '/publish',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'first_name' => 'Alexandr',
                'last_name' => 'Doe',
            ])
        );

        self::assertEquals(201, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());

        $content = json_decode($content, true);

        self::assertEquals('published', $content['document']['status']);
    }
}

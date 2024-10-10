<?php

declare(strict_types=1);

namespace App\Test\Functional\User\Document;

use App\Test\Functional\DbWebTestCase;
use App\Test\Functional\User\SignUpTrait;

class PublishDocumentTest extends DbWebTestCase
{
    use SignUpTrait;

    private const CREATE_DOCUMENT_URI = '/api/v1/document';

    public function testPublish(): void
    {
        $authContent = $this->signUp();

        $this->client->request(
            'POST',
            self::CREATE_DOCUMENT_URI,
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer :' . $authContent['token'],
            ],
            content: json_encode([
                'payload' => [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                ]
            ])
        );
        $content = json_decode($this->client->getResponse()->getContent(), true);

        $this->client->request(
            'POST',
            self::CREATE_DOCUMENT_URI . '/'. $content['document']['id'] . '/publish',
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer :' . $authContent['token'],
            ],
            content: json_encode([
                'payload' => [
                    'first_name' => 'Alexandr',
                    'last_name' => 'Doe',
                ]
            ])
        );

        self::assertEquals(201, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());

        $content = json_decode($content, true);

        self::assertEquals('published', $content['document']['status']);
    }
}

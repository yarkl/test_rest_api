<?php

declare(strict_types=1);

namespace App\Test\Functional\User\Document;

use App\Test\Functional\DbWebTestCase;
use App\Test\Functional\User\SignUpTrait;

class UpdateDocumentTest extends DbWebTestCase
{
    use SignUpTrait;

    private const CREATE_DOCUMENT_URI = '/api/v1/document';

    public function testUpdate(): void
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

        $updatedDocument = [
            'first_name' => 'Alexandr',
            'last_name' => 'Doe',
        ];

        $this->client->request(
            'PATCH',
            self::CREATE_DOCUMENT_URI . '/'. $content['document']['id'],
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer :' . $authContent['token']
            ],
            content: json_encode(['payload' => $updatedDocument])
        );

        self::assertEquals(201, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());

        $content = json_decode($content, true);

        self::assertEquals($updatedDocument, $content['document']['payload']);
    }
}

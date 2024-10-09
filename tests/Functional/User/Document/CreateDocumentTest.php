<?php

declare(strict_types=1);

namespace App\Test\Functional\User\Document;

use App\Test\Functional\DbWebTestCase;
use App\Test\Functional\User\SignUpTrait;

class CreateDocumentTest extends DbWebTestCase
{
    use SignUpTrait;

    private const CREATE_DOCUMENT_URI = '/api/v1/document';

    public function testCreate(): void
    {
        $authContent = $this->signUp();

        $payload = [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ];

        $this->client->request(
            'POST',
            self::CREATE_DOCUMENT_URI,
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer :' . $authContent['token'],
            ],
            content: json_encode($payload)
        );

        self::assertEquals(201, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());

        $content = json_decode($content, true);

        self::assertEquals($payload, $content['document']['payload']);
    }
}

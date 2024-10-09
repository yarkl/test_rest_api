<?php

namespace App\Test\Functional\User;

trait SignUpTrait
{
    private const AUTHENTICATION_URI = '/api/v1/signup';

    public function signUp(): array
    {
        $this->client->request(
            'POST',
            self::AUTHENTICATION_URI,
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(['login' => 'John Doe'])
        );

        return json_decode($this->client->getResponse()->getContent(), true);
    }
}
<?php

declare(strict_types=1);

namespace App\Test\Functional\User\SignUp\JWT;

use App\Test\Functional\DbWebTestCase;
use App\Test\Functional\User\SignUpTrait;

class JWTSignupTest extends DbWebTestCase
{
    use SignUpTrait;

    public function testSignup(): void
    {
        $this->signUp();

        self::assertEquals(201, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());

        $content = json_decode($content, true);

        $this->assertNotEmpty($token = $content['token']);
        $this->assertNotEmpty($refreshToken = $content['refreshToken']);
    }
}

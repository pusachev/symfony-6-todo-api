<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;

class AuthControllerTest extends AbstractApiControllerTestCase
{
    public function tearDown(): void
    {
        $this->removeUser();
        parent::tearDown();
    }

    public function testRegister(): void
    {
        $this->client->request('POST', '/api/register', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'email' => self::API_USER_EMAIL,
            'password' => self::API_PASSWORD,
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

    }

    public function testLogin(): void
    {
        $this->client->request('POST', '/api/register', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'email' => self::API_USER_EMAIL,
            'password' => self::API_PASSWORD,
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $this->client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'email' => self::API_USER_EMAIL,
            'password' => self::API_PASSWORD,
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}

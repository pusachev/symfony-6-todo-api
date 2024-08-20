<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;

class AuthControllerTest extends AbstractApiControllerTestCase
{
    /** {@inheritDoc} */
    public function tearDown(): void
    {
        $this->removeUser();
        parent::tearDown();
    }

    /**
     * Test register user
     *
     * @return void
     */
    public function testRegister(): void
    {
        $this->client->request(
            'POST',
            '/api/register',
            [],
            [],
            $this->getHeaders(),
            json_encode([
                'email' => self::API_USER_EMAIL,
                'password' => self::API_PASSWORD,
                ]
            )
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    /**
     * Test login existing user
     *
     * @return void
     */
    public function testLogin(): void
    {
        $this->client->request(
            'POST',
            '/api/register',
            [],
            [],
            $this->getHeaders(),
            json_encode([
                'email' => self::API_USER_EMAIL,
                'password' => self::API_PASSWORD,
                ]
            )
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            $this->getHeaders(),
            json_encode([
                'email' => self::API_USER_EMAIL,
                'password' => self::API_PASSWORD,
                ]
            )
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}

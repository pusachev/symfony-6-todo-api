<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractApiControllerTestCase extends WebTestCase
{
    protected const API_USER_EMAIL = 'user@example.com';
    protected const API_PASSWORD = 'password';

    /** @var KernelBrowser|null  */
    protected ?KernelBrowser $client = null;

    /** @var EntityManagerInterface|null  */
    protected ?EntityManagerInterface $entityManager = null;

    /** {@inheritDoc} */
    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client
                                    ->getContainer()
                                    ->get('doctrine.orm.entity_manager');;
    }

    /** {@inheritDoc} */
    public function tearDown(): void
    {
        $this->ensureKernelShutdown();
    }

    /**
     * Create user per test
     *
     * @param string $email
     * @param string $password
     *
     * @return void
     */
    protected function createApiUser(
        string $email = self::API_USER_EMAIL,
        string $password = self::API_PASSWORD,
    ): void
    {
        $entityManager = $this->client
                              ->getContainer()
                              ->get('doctrine.orm.entity_manager');
        $passwordHasher = $this->client->getContainer()
                               ->get('security.user_password_hasher');

        $user = new User();
        $user->setEmail($email);
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                $password
            )
        );

        $entityManager->persist($user);
        $entityManager->flush();
    }

    /**
     * Remove user after test
     *
     * @return void
     */
    protected function removeUser(): void
    {

        $repo = $this->entityManager
                     ->getRepository(User::class);

        $users = $repo->findAll();

        foreach ($users as $user) {
            $this->entityManager->remove($user);
        }

        $this->entityManager->flush();
    }

    /**
     * @param string $email
     * @param string $password
     *
     * @return string
     */
    protected function getAuthToken(
        string $email = self::API_USER_EMAIL,
        string $password = self::API_PASSWORD
    ): string
    {
        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            $this->getHeaders(),
            json_encode(
                [
                    'email' => $email,
                    'password' => $password,
                ]
            )
        );

        $data = json_decode(
            $this->client
                 ->getResponse()
                 ->getContent(),
            true
        );
        $token = $data['token'];

        return $token;
    }

    protected function getHeaders(?string $token = null): array
    {
        $headers = [
            'CONTENT_TYPE' => 'application/json'
        ];

        if ($token) {
            $headers['HTTP_AUTHORIZATION'] = sprintf('Bearer %s' , $token);
        }

        return $headers;
    }
}

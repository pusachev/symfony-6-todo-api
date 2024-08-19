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
                                    ->get('doctrine.orm.entity_manager');
    }

    /** {@inheritDoc} */
    public function tearDown(): void
    {
        $this->ensureKernelShutdown();
    }

    /** @return void */
    protected function createApiUser(): void
    {
        $entityManager = $this->client
                              ->getContainer()
                              ->get('doctrine.orm.entity_manager');
        $passwordHasher = $this->client->getContainer()
                               ->get('security.user_password_hasher');

        $user = new User();
        $user->setEmail(self::API_USER_EMAIL);
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                self::API_PASSWORD
            )
        );

        $entityManager->persist($user);
        $entityManager->flush();
    }

    /** @return void */
    protected function removeUser(): void
    {

        $repo = $this->entityManager
                     ->getRepository(User::class);

        $user = $repo->findOneBy(['email' => self::API_USER_EMAIL]);

        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    /**
     * @return string
     */
    protected function getAuthToken(): string
    {
        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => self::API_USER_EMAIL,
                'password' => self::API_PASSWORD,
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
}

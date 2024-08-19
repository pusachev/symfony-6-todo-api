<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends AbstractApiControllerTestCase
{
    /** {@inheritDoc} */
    public function setUp(): void
    {
        parent::setUp();
        $this->createApiUser();
    }

    /** {@inheritDoc} */
    public function tearDown(): void
    {
        $this->removeUser();
        parent::tearDown();
    }

    public function testCreateTask(): void
    {
        $client = $this->client;

        $token = $this->getAuthToken();

        $client->request('POST', '/api/tasks', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => sprintf('Bearer %s', $token),
        ], json_encode([
            'title' => 'Test Task',
            'description' => 'Test Description',
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $data = json_decode(
            $client->getResponse()
                   ->getContent(),
            true
        );

        $this->assertArrayHasKey('id', $data);
        $this->assertSame('Test Task', $data['title']);
        $this->assertSame('Test Description', $data['description']);
        $this->assertSame(self::API_USER_EMAIL, $data['user']['email']);
    }

    public function testViewTask(): void
    {
        $client = $this->client;

        $token = $this->getAuthToken();

        $client->request('POST', '/api/tasks', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => sprintf('Bearer %s', $token),
        ], json_encode([
            'title' => 'Test Task',
            'description' => 'Test Description',
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $data);
        $this->assertSame('Test Task', $data['title']);
        $this->assertSame('Test Description', $data['description']);
        $this->assertSame(self::API_USER_EMAIL, $data['user']['email']);

        $id = $data['id'];

        $client->request('GET', sprintf('/api/tasks/%d', $id), [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => sprintf('Bearer %s', $token),
        ]);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertSame('Test Task', $data['title']);
        $this->assertSame('Test Description', $data['description']);
        $this->assertSame(self::API_USER_EMAIL, $data['user']['email']);
    }

    public function testUpdateTask(): void
    {
        $client = $this->client;

        $token = $this->getAuthToken();

        $client->request('POST', '/api/tasks', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => sprintf('Bearer %s', $token),
        ], json_encode([
            'title' => 'Test Task',
            'description' => 'Test Description',
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertSame('Test Task', $data['title']);
        $this->assertSame('Test Description', $data['description']);
        $this->assertSame('user@example.com', $data['user']['email']);

        $id = $data['id'];

        $client->request('PUT', sprintf('/api/tasks/%d', $id), [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => sprintf('Bearer %s', $token),
        ], json_encode([
            'title' => 'Test Task Edited',
            'description' => 'Test Description Edited',
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertSame('Test Task Edited', $data['title']);
        $this->assertSame('Test Description Edited', $data['description']);
        $this->assertSame(self::API_USER_EMAIL, $data['user']['email']);

        $client->request('GET', sprintf('/api/tasks/%d', $id), [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => sprintf('Bearer %s', $token),
        ]);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertSame('Test Task Edited', $data['title']);
        $this->assertSame('Test Description Edited', $data['description']);
        $this->assertSame(self::API_USER_EMAIL, $data['user']['email']);
    }

    public function testRemoveTask(): void
    {
        $client = $this->client;

        $token = $this->getAuthToken();

        $client->request('POST', '/api/tasks', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => sprintf('Bearer %s', $token),
        ], json_encode([
            'title' => 'Test Task',
            'description' => 'Test Description',
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertSame('Test Task', $data['title']);
        $this->assertSame('Test Description', $data['description']);
        $this->assertSame(self::API_USER_EMAIL, $data['user']['email']);

        $id = $data['id'];

        $client->request('DELETE', sprintf('/api/tasks/%d', $id), [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => sprintf('Bearer %s', $token),
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}

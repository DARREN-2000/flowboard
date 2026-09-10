<?php
declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testGetTasksRequiresAuthentication(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/projects/00000000-0000-0000-0000-000000000001/tasks');

        $this->assertResponseStatusCodeSame(401);
    }

    public function testCreateTaskRequiresAuthentication(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/projects/00000000-0000-0000-0000-000000000001/tasks', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'title' => 'Test Task',
        ]));

        $this->assertResponseStatusCodeSame(401);
    }

    public function testUpdateTaskRequiresAuthentication(): void
    {
        $client = static::createClient();
        $client->request('PUT', '/api/tasks/00000000-0000-0000-0000-000000000001', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'title' => 'Updated Title',
        ]));

        $this->assertResponseStatusCodeSame(401);
    }

    public function testMoveTaskRequiresAuthentication(): void
    {
        $client = static::createClient();
        $client->request('PATCH', '/api/tasks/00000000-0000-0000-0000-000000000001/move', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'status' => 'IN_PROGRESS',
            'position' => 0,
        ]));

        $this->assertResponseStatusCodeSame(401);
    }

    public function testDeleteTaskRequiresAuthentication(): void
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/tasks/00000000-0000-0000-0000-000000000001');

        $this->assertResponseStatusCodeSame(401);
    }

    public function testGetTaskCommentsRequiresAuthentication(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/tasks/00000000-0000-0000-0000-000000000001/comments');

        $this->assertResponseStatusCodeSame(401);
    }
}

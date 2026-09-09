<?php
declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testGetTasks(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/projects/fake-uuid/tasks');

        $this->assertResponseStatusCodeSame(401);
    }
}

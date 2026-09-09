<?php
declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectControllerTest extends WebTestCase
{
    public function testGetProjects(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/workspaces/fake-uuid/projects');

        $this->assertResponseStatusCodeSame(401);
    }
}

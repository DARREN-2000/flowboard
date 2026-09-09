<?php
declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WorkspaceControllerTest extends WebTestCase
{
    public function testGetWorkspaces(): void
    {
        $client = static::createClient();

        // This would normally be authenticated, but we're just scaffolding the test
        $client->request('GET', '/api/workspaces');

        $this->assertResponseStatusCodeSame(401); // Unauthorized
    }
}

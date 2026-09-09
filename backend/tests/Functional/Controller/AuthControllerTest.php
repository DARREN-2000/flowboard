<?php
declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
{
    public function testRegister(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/auth/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'fullName' => 'New User'
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);
    }
}

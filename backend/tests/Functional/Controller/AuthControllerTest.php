<?php
declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
{
    public function testRegisterEndpointExists(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/auth/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'fullName' => 'New User'
        ]));

        // Should succeed or return validation error, not 404 or 500
        $this->assertContains($client->getResponse()->getStatusCode(), [201, 422]);
    }

    public function testRegisterWithMissingFields(): void
    {
        $client = static::createClient();

        // Missing password and fullName
        $client->request('POST', '/api/auth/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@example.com',
        ]));

        // Should return 422 for validation failure
        $this->assertContains($client->getResponse()->getStatusCode(), [400, 422]);
    }

    public function testRegisterWithInvalidEmail(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/auth/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'not-an-email',
            'password' => 'password123',
            'fullName' => 'Test User'
        ]));

        $this->assertContains($client->getResponse()->getStatusCode(), [400, 422]);
    }

    public function testLoginEndpointExists(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/auth/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ]));

        // Should return 401 for bad credentials
        $this->assertResponseStatusCodeSame(401);
    }

    public function testMeEndpointRequiresAuth(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/auth/me');

        $this->assertResponseStatusCodeSame(401);
    }

    public function testRegisterIsPubliclyAccessible(): void
    {
        $client = static::createClient();

        // The register endpoint should not return 401 — it's a public route
        $client->request('POST', '/api/auth/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'public@example.com',
            'password' => 'testpassword',
            'fullName' => 'Public User'
        ]));

        $this->assertNotEquals(401, $client->getResponse()->getStatusCode());
    }
}

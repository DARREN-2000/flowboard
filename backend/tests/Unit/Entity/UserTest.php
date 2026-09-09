<?php
declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserCreation(): void
    {
        $user = new User('test@example.com', 'Test User');
        $user->setPasswordHash('hashed_password');

        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('Test User', $user->getFullName());
        $this->assertEquals('hashed_password', $user->getPassword());
        $this->assertNotNull($user->getId());
    }
}

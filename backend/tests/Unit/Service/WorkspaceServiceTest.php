<?php
declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\DTO\Request\CreateWorkspaceRequest;
use App\Entity\User;
use App\Service\ActivityService;
use App\Service\WorkspaceService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class WorkspaceServiceTest extends TestCase
{
    public function testCreateWorkspace(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $activity = $this->createMock(ActivityService::class);
        
        $em->expects($this->exactly(2))->method('persist');
        $em->expects($this->once())->method('flush');

        $service = new WorkspaceService($em, $activity);

        $user = new User('test@example.com', 'Test User');
        $req = CreateWorkspaceRequest::fromRequest(new \Symfony\Component\HttpFoundation\Request([], ['name' => 'My Workspace']));
        
        $workspace = $service->createWorkspace($req, $user);

        $this->assertEquals('My Workspace', $workspace->getName());
        $this->assertSame($user, $workspace->getCreatedBy());
    }
}

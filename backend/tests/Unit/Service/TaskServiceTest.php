<?php
declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\DTO\Request\CreateTaskRequest;
use App\Entity\Project;
use App\Entity\User;
use App\Entity\Workspace;
use App\Repository\UserRepository;
use App\Service\ActivityService;
use App\Service\MercurePublisher;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class TaskServiceTest extends TestCase
{
    public function testCreateTask(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $activity = $this->createMock(ActivityService::class);
        $userRepo = $this->createMock(UserRepository::class);
        $mercure = $this->createMock(MercurePublisher::class);

        $em->expects($this->once())->method('persist');
        $em->expects($this->once())->method('flush');

        $service = new TaskService($em, $activity, $userRepo, $mercure);

        $user = new User('test@example.com', 'Test User');
        $workspace = new Workspace('Workspace', 'slug', $user);
        $project = new Project($workspace, 'Project', 'p-slug', $user);

        $req = CreateTaskRequest::fromRequest(new Request([], ['title' => 'New Task']));

        $task = $service->createTask($project, $req, $user);

        $this->assertEquals('New Task', $task->getTitle());
        $this->assertSame($project, $task->getProject());
    }
}

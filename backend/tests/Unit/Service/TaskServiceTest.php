<?php
declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\DTO\Request\CreateTaskRequest;
use App\DTO\Request\MoveTaskRequest;
use App\DTO\Request\UpdateTaskRequest;
use App\Entity\Project;
use App\Entity\Task;
use App\Entity\User;
use App\Entity\Workspace;
use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use App\Repository\UserRepository;
use App\Service\ActivityService;
use App\Service\MercurePublisher;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class TaskServiceTest extends TestCase
{
    private TaskService $service;
    private EntityManagerInterface $em;
    private ActivityService $activity;
    private UserRepository $userRepo;
    private MercurePublisher $mercure;
    private User $user;
    private Workspace $workspace;
    private Project $project;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->activity = $this->createMock(ActivityService::class);
        $this->userRepo = $this->createMock(UserRepository::class);
        $this->mercure = $this->createMock(MercurePublisher::class);

        $this->service = new TaskService($this->em, $this->activity, $this->userRepo, $this->mercure);

        $this->user = new User('test@example.com', 'Test User');
        $this->workspace = new Workspace('Workspace', 'test-ws', $this->user);
        $this->project = new Project($this->workspace, 'Project', 'test-proj', $this->user);
    }

    public function testCreateTask(): void
    {
        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');
        $this->activity->expects($this->once())->method('logActivity');

        $request = $this->createTaskRequest(['title' => 'New Task']);
        $task = $this->service->createTask($this->project, $request, $this->user);

        $this->assertEquals('New Task', $task->getTitle());
        $this->assertSame($this->project, $task->getProject());
        $this->assertEquals(TaskStatus::BACKLOG, $task->getStatus());
        $this->assertEquals(TaskPriority::MEDIUM, $task->getPriority());
    }

    public function testCreateTaskWithAllFields(): void
    {
        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');

        $request = $this->createTaskRequest([
            'title' => 'Full Task',
            'description' => 'A detailed description',
            'status' => 'IN_PROGRESS',
            'priority' => 'HIGH',
            'dueDate' => '2026-12-31',
        ]);

        $task = $this->service->createTask($this->project, $request, $this->user);

        $this->assertEquals('Full Task', $task->getTitle());
        $this->assertEquals('A detailed description', $task->getDescription());
        $this->assertEquals(TaskStatus::IN_PROGRESS, $task->getStatus());
        $this->assertEquals(TaskPriority::HIGH, $task->getPriority());
        $this->assertNotNull($task->getDueDate());
    }

    public function testUpdateTaskTitle(): void
    {
        $task = new Task($this->project, 'Original Title', TaskStatus::BACKLOG, TaskPriority::LOW);

        $this->em->expects($this->once())->method('flush');
        $this->activity->expects($this->once())->method('logActivity');

        $request = $this->createUpdateRequest(['title' => 'Updated Title']);
        $updated = $this->service->updateTask($task, $request, $this->user);

        $this->assertEquals('Updated Title', $updated->getTitle());
    }

    public function testUpdateTaskStatus(): void
    {
        $task = new Task($this->project, 'Task', TaskStatus::BACKLOG, TaskPriority::LOW);

        $this->em->expects($this->once())->method('flush');

        $request = $this->createUpdateRequest(['status' => 'IN_PROGRESS']);
        $updated = $this->service->updateTask($task, $request, $this->user);

        $this->assertEquals(TaskStatus::IN_PROGRESS, $updated->getStatus());
    }

    public function testUpdateTaskWithInvalidStatusThrowsException(): void
    {
        $task = new Task($this->project, 'Task', TaskStatus::BACKLOG, TaskPriority::LOW);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid task status');

        $request = $this->createUpdateRequest(['status' => 'INVALID_STATUS']);
        $this->service->updateTask($task, $request, $this->user);
    }

    public function testUpdateTaskWithInvalidPriorityThrowsException(): void
    {
        $task = new Task($this->project, 'Task', TaskStatus::BACKLOG, TaskPriority::LOW);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid task priority');

        $request = $this->createUpdateRequest(['priority' => 'SUPER_HIGH']);
        $this->service->updateTask($task, $request, $this->user);
    }

    public function testMoveTask(): void
    {
        $task = new Task($this->project, 'Task', TaskStatus::BACKLOG, TaskPriority::MEDIUM);

        $this->em->expects($this->once())->method('flush');
        $this->activity->expects($this->once())->method('logActivity');

        $request = MoveTaskRequest::fromRequest(new Request([], [], [], [], [], [], json_encode([
            'status' => 'IN_PROGRESS',
            'position' => 2,
        ])));

        $moved = $this->service->moveTask($task, $request, $this->user);

        $this->assertEquals(TaskStatus::IN_PROGRESS, $moved->getStatus());
        $this->assertEquals(2, $moved->getPosition());
    }

    public function testMoveTaskWithInvalidStatusThrowsException(): void
    {
        $task = new Task($this->project, 'Task', TaskStatus::BACKLOG, TaskPriority::MEDIUM);

        $this->expectException(\InvalidArgumentException::class);

        $request = MoveTaskRequest::fromRequest(new Request([], [], [], [], [], [], json_encode([
            'status' => 'BOGUS',
            'position' => 0,
        ])));

        $this->service->moveTask($task, $request, $this->user);
    }

    public function testDeleteTask(): void
    {
        $task = new Task($this->project, 'To Delete', TaskStatus::BACKLOG, TaskPriority::LOW);

        $this->em->expects($this->once())->method('remove');
        $this->em->expects($this->once())->method('flush');
        $this->activity->expects($this->once())->method('logActivity');

        $this->service->deleteTask($task, $this->user);
    }

    public function testMercurePublishOnCreate(): void
    {
        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');
        $this->mercure->expects($this->once())->method('publish');

        $request = $this->createTaskRequest(['title' => 'Published Task']);
        $this->service->createTask($this->project, $request, $this->user);
    }

    private function createTaskRequest(array $data): CreateTaskRequest
    {
        return CreateTaskRequest::fromRequest(new Request([], $data));
    }

    private function createUpdateRequest(array $data): UpdateTaskRequest
    {
        return UpdateTaskRequest::fromRequest(new Request([], [], [], [], [], [], json_encode($data)));
    }
}

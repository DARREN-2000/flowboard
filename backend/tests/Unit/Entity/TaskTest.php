<?php
declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Project;
use App\Entity\Task;
use App\Entity\User;
use App\Entity\Workspace;
use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testTaskCreation(): void
    {
        $user = new User('test@example.com', 'Test');
        $workspace = new Workspace('Workspace', 'slug', $user);
        $project = new Project($workspace, 'Project', 'p-slug', $user);

        $task = new Task($project, 'Task Title');

        $this->assertEquals('Task Title', $task->getTitle());
        $this->assertEquals(TaskStatus::BACKLOG, $task->getStatus());
        $this->assertEquals(TaskPriority::MEDIUM, $task->getPriority());
        $this->assertSame($project, $task->getProject());
    }
}

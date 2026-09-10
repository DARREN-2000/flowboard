<?php
declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Comment;
use App\Entity\Project;
use App\Entity\Task;
use App\Entity\User;
use App\Entity\Workspace;
use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private User $user;
    private Workspace $workspace;
    private Project $project;

    protected function setUp(): void
    {
        $this->user = new User('test@example.com', 'Test User');
        $this->workspace = new Workspace('Workspace', 'test-ws', $this->user);
        $this->project = new Project($this->workspace, 'Project', 'test-proj', $this->user);
    }

    public function testTaskCreation(): void
    {
        $task = new Task($this->project, 'Task Title');

        $this->assertEquals('Task Title', $task->getTitle());
        $this->assertEquals(TaskStatus::BACKLOG, $task->getStatus());
        $this->assertEquals(TaskPriority::MEDIUM, $task->getPriority());
        $this->assertSame($this->project, $task->getProject());
        $this->assertNull($task->getAssignee());
        $this->assertNull($task->getDueDate());
        $this->assertNull($task->getParentTask());
    }

    public function testTaskCreationWithCustomStatusAndPriority(): void
    {
        $task = new Task($this->project, 'Urgent Task', TaskStatus::IN_PROGRESS, TaskPriority::CRITICAL);

        $this->assertEquals(TaskStatus::IN_PROGRESS, $task->getStatus());
        $this->assertEquals(TaskPriority::CRITICAL, $task->getPriority());
    }

    public function testSetTitle(): void
    {
        $task = new Task($this->project, 'Original');
        $task->setTitle('Updated');

        $this->assertEquals('Updated', $task->getTitle());
    }

    public function testSetDescription(): void
    {
        $task = new Task($this->project, 'Task');
        $this->assertNull($task->getDescription());

        $task->setDescription('A detailed description');
        $this->assertEquals('A detailed description', $task->getDescription());
    }

    public function testSetStatus(): void
    {
        $task = new Task($this->project, 'Task');
        $this->assertEquals(TaskStatus::BACKLOG, $task->getStatus());

        $task->setStatus(TaskStatus::IN_PROGRESS);
        $this->assertEquals(TaskStatus::IN_PROGRESS, $task->getStatus());
    }

    public function testSetPriority(): void
    {
        $task = new Task($this->project, 'Task');
        $task->setPriority(TaskPriority::HIGH);
        $this->assertEquals(TaskPriority::HIGH, $task->getPriority());
    }

    public function testSetAssignee(): void
    {
        $task = new Task($this->project, 'Task');
        $assignee = new User('dev@example.com', 'Developer');

        $task->setAssignee($assignee);
        $this->assertSame($assignee, $task->getAssignee());
    }

    public function testSetDueDate(): void
    {
        $task = new Task($this->project, 'Task');
        $date = new \DateTimeImmutable('2026-12-31');

        $task->setDueDate($date);
        $this->assertEquals($date, $task->getDueDate());
    }

    public function testSetPosition(): void
    {
        $task = new Task($this->project, 'Task');
        $task->setPosition(5);
        $this->assertEquals(5, $task->getPosition());
    }

    public function testParentChildRelationship(): void
    {
        $parent = new Task($this->project, 'Parent Task');
        $child = new Task($this->project, 'Child Task');

        $child->setParentTask($parent);
        $this->assertSame($parent, $child->getParentTask());
    }

    public function testTaskStatusTransitions(): void
    {
        // Test valid transitions
        $this->assertTrue(TaskStatus::BACKLOG->canTransitionTo(TaskStatus::TODO));
        $this->assertTrue(TaskStatus::BACKLOG->canTransitionTo(TaskStatus::IN_PROGRESS));
        $this->assertTrue(TaskStatus::IN_PROGRESS->canTransitionTo(TaskStatus::DONE));

        // Test invalid transitions
        $this->assertFalse(TaskStatus::BACKLOG->canTransitionTo(TaskStatus::DONE));
        $this->assertFalse(TaskStatus::BACKLOG->canTransitionTo(TaskStatus::IN_REVIEW));
    }

    public function testTaskStatusValues(): void
    {
        $values = TaskStatus::values();

        $this->assertContains('BACKLOG', $values);
        $this->assertContains('TODO', $values);
        $this->assertContains('IN_PROGRESS', $values);
        $this->assertContains('IN_REVIEW', $values);
        $this->assertContains('DONE', $values);
        $this->assertCount(5, $values);
    }

    public function testTaskPriorityValues(): void
    {
        $values = TaskPriority::values();

        $this->assertContains('LOW', $values);
        $this->assertContains('MEDIUM', $values);
        $this->assertContains('HIGH', $values);
        $this->assertContains('CRITICAL', $values);
        $this->assertCount(4, $values);
    }
}

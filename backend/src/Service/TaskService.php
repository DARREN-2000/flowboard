<?php
declare(strict_types=1);

namespace App\Service;

use App\DTO\Request\CreateTaskRequest;
use App\DTO\Request\UpdateTaskRequest;
use App\DTO\Request\MoveTaskRequest;
use App\Entity\Project;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    private EntityManagerInterface $em;
    private ActivityService $activityService;
    private UserRepository $userRepository;
    private MercurePublisher $mercurePublisher;

    public function __construct(EntityManagerInterface $em, ActivityService $activityService, UserRepository $userRepository, MercurePublisher $mercurePublisher)
    {
        $this->em = $em;
        $this->activityService = $activityService;
        $this->userRepository = $userRepository;
        $this->mercurePublisher = $mercurePublisher;
    }

    public function createTask(Project $project, CreateTaskRequest $request, User $user): Task
    {
        $status = TaskStatus::tryFrom($request->status) ?? TaskStatus::BACKLOG;
        $priority = TaskPriority::tryFrom($request->priority) ?? TaskPriority::MEDIUM;

        $task = new Task($project, $request->title, $status, $priority);
        $task->setDescription($request->description);

        if ($request->assigneeId) {
            $assignee = $this->userRepository->find($request->assigneeId);
            if ($assignee) {
                $task->setAssignee($assignee);
            }
        }

        if ($request->dueDate) {
            $task->setDueDate(new \DateTimeImmutable($request->dueDate));
        }

        if ($request->parentTaskId) {
            $parent = $this->em->getRepository(Task::class)->find($request->parentTaskId);
            if ($parent && $parent->getProject() === $project) {
                $task->setParentTask($parent);
            }
        }

        $this->em->persist($task);
        $this->em->flush();

        $this->activityService->logActivity($project->getWorkspace(), $user, 'task.created', ['title' => $task->getTitle()], $project, $task);
        $this->publishTaskUpdate($task);

        return $task;
    }

    public function updateTask(Task $task, UpdateTaskRequest $request, User $user): Task
    {
        if ($request->title !== null) {
            $task->setTitle($request->title);
        }
        if ($request->description !== null) {
            $task->setDescription($request->description);
        }
        if ($request->status !== null) {
            $status = TaskStatus::tryFrom($request->status);
            if ($status) $task->setStatus($status);
        }
        if ($request->priority !== null) {
            $priority = TaskPriority::tryFrom($request->priority);
            if ($priority) $task->setPriority($priority);
        }
        if ($request->assigneeId !== null) {
            $assignee = $this->userRepository->find($request->assigneeId);
            $task->setAssignee($assignee);
        }
        if ($request->dueDate !== null) {
            $task->setDueDate(new \DateTimeImmutable($request->dueDate));
        }

        $this->em->flush();

        $this->activityService->logActivity($task->getProject()->getWorkspace(), $user, 'task.updated', ['title' => $task->getTitle()], $task->getProject(), $task);
        $this->publishTaskUpdate($task);

        return $task;
    }

    public function moveTask(Task $task, MoveTaskRequest $request, User $user): Task
    {
        $status = TaskStatus::tryFrom($request->status);
        if ($status) {
            $task->setStatus($status);
        }
        
        $task->setPosition($request->position);
        $this->em->flush();
        
        $this->publishTaskUpdate($task);
        
        return $task;
    }

    public function deleteTask(Task $task, User $user): void
    {
        $project = $task->getProject();
        $this->activityService->logActivity($project->getWorkspace(), $user, 'task.deleted', ['title' => $task->getTitle()], $project);
        
        $id = (string) $task->getId();
        $this->em->remove($task);
        $this->em->flush();
        
        $this->mercurePublisher->publish(
            sprintf('/projects/%s/tasks', $project->getId()),
            ['action' => 'deleted', 'id' => $id]
        );
    }
    
    private function publishTaskUpdate(Task $task): void
    {
        $this->mercurePublisher->publish(
            sprintf('/projects/%s/tasks', $task->getProject()->getId()),
            [
                'action' => 'updated',
                'task' => \App\DTO\Response\TaskResponse::fromEntity($task)->toArray()
            ]
        );
    }
}

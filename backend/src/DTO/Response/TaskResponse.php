<?php
declare(strict_types=1);

namespace App\DTO\Response;

use App\Entity\Task;

class TaskResponse
{
    public readonly string $id;
    public readonly string $title;
    public readonly ?string $description;
    public readonly string $status;
    public readonly string $priority;
    public readonly ?string $assigneeId;
    public readonly ?string $parentTaskId;
    public readonly string $projectId;
    public readonly ?string $dueDate;
    public readonly int $position;

    private function __construct(string $id, string $title, ?string $description, string $status, string $priority, ?string $assigneeId, ?string $parentTaskId, string $projectId, ?string $dueDate, int $position)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->priority = $priority;
        $this->assigneeId = $assigneeId;
        $this->parentTaskId = $parentTaskId;
        $this->projectId = $projectId;
        $this->dueDate = $dueDate;
        $this->position = $position;
    }

    public static function fromEntity(Task $task): self
    {
        return new self(
            (string) $task->getId(),
            $task->getTitle(),
            $task->getDescription(),
            $task->getStatus()->value,
            $task->getPriority()->value,
            $task->getAssignee() ? (string) $task->getAssignee()->getId() : null,
            $task->getParentTask() ? (string) $task->getParentTask()->getId() : null,
            (string) $task->getProject()->getId(),
            $task->getDueDate() ? $task->getDueDate()->format(\DateTimeInterface::ATOM) : null,
            $task->getPosition()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'assigneeId' => $this->assigneeId,
            'parentTaskId' => $this->parentTaskId,
            'projectId' => $this->projectId,
            'dueDate' => $this->dueDate,
            'position' => $this->position,
        ];
    }
}

<?php
declare(strict_types=1);

namespace App\DTO\Request;

use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateTaskRequest
{
    #[Assert\Length(min: 1, max: 255, minMessage: 'Title must not be empty', maxMessage: 'Title must be at most 255 characters')]
    public readonly ?string $title;

    #[Assert\Length(max: 10000)]
    public readonly ?string $description;

    #[Assert\Choice(callback: [TaskStatus::class, 'values'], message: 'Invalid task status. Valid values: {{ choices }}')]
    public readonly ?string $status;

    #[Assert\Choice(callback: [TaskPriority::class, 'values'], message: 'Invalid priority. Valid values: {{ choices }}')]
    public readonly ?string $priority;

    #[Assert\Uuid(message: 'Assignee ID must be a valid UUID')]
    public readonly ?string $assigneeId;

    #[Assert\Date(message: 'Due date must be a valid date (YYYY-MM-DD)')]
    public readonly ?string $dueDate;

    private function __construct(?string $title, ?string $description, ?string $status, ?string $priority, ?string $assigneeId, ?string $dueDate)
    {
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->priority = $priority;
        $this->assigneeId = $assigneeId;
        $this->dueDate = $dueDate;
    }

    public static function fromRequest(Request $request): self
    {
        $payload = $request->toArray();
        return new self(
            $payload['title'] ?? null,
            $payload['description'] ?? null,
            $payload['status'] ?? null,
            $payload['priority'] ?? null,
            $payload['assigneeId'] ?? null,
            $payload['dueDate'] ?? null
        );
    }
}

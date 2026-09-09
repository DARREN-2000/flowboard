<?php
declare(strict_types=1);

namespace App\DTO\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTaskRequest
{
    #[Assert\NotBlank]
    public readonly string $title;
    public readonly ?string $description;
    public readonly string $status;
    public readonly string $priority;
    public readonly ?string $assigneeId;
    public readonly ?string $dueDate;
    public readonly ?string $parentTaskId;

    private function __construct(string $title, ?string $description, string $status, string $priority, ?string $assigneeId, ?string $dueDate, ?string $parentTaskId)
    {
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->priority = $priority;
        $this->assigneeId = $assigneeId;
        $this->dueDate = $dueDate;
        $this->parentTaskId = $parentTaskId;
    }

    public static function fromRequest(Request $request): self
    {
        $payload = $request->toArray();
        return new self(
            $payload['title'] ?? '',
            $payload['description'] ?? null,
            $payload['status'] ?? 'BACKLOG',
            $payload['priority'] ?? 'MEDIUM',
            $payload['assigneeId'] ?? null,
            $payload['dueDate'] ?? null,
            $payload['parentTaskId'] ?? null
        );
    }
}

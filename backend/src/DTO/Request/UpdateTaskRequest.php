<?php
declare(strict_types=1);

namespace App\DTO\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateTaskRequest
{
    public readonly ?string $title;
    public readonly ?string $description;
    public readonly ?string $status;
    public readonly ?string $priority;
    public readonly ?string $assigneeId;
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

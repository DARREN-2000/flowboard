<?php
declare(strict_types=1);

namespace App\DTO\Request;

use App\Enum\TaskStatus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class MoveTaskRequest
{
    #[Assert\NotBlank(message: 'Status is required')]
    #[Assert\Choice(callback: [TaskStatus::class, 'values'], message: 'Invalid task status. Valid values: {{ choices }}')]
    public readonly string $status;

    #[Assert\NotNull(message: 'Position is required')]
    #[Assert\PositiveOrZero(message: 'Position must be a non-negative integer')]
    public readonly int $position;

    private function __construct(string $status, int $position)
    {
        $this->status = $status;
        $this->position = $position;
    }

    public static function fromRequest(Request $request): self
    {
        $payload = $request->toArray();
        return new self(
            $payload['status'] ?? '',
            (int) ($payload['position'] ?? 0)
        );
    }
}

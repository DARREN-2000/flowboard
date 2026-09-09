<?php
declare(strict_types=1);

namespace App\DTO\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class MoveTaskRequest
{
    #[Assert\NotBlank]
    public readonly string $status;

    #[Assert\NotNull]
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
            $payload['position'] ?? 0
        );
    }
}

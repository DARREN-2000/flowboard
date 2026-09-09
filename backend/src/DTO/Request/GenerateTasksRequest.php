<?php
declare(strict_types=1);

namespace App\DTO\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class GenerateTasksRequest
{
    #[Assert\NotBlank]
    public readonly string $prompt;

    private function __construct(string $prompt)
    {
        $this->prompt = $prompt;
    }

    public static function fromRequest(Request $request): self
    {
        $payload = $request->toArray();
        return new self(
            $payload['prompt'] ?? ''
        );
    }
}

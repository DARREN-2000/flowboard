<?php
declare(strict_types=1);

namespace App\DTO\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CreateWorkspaceRequest
{
    #[Assert\NotBlank]
    public readonly string $name;

    public readonly ?string $description;

    private function __construct(string $name, ?string $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

    public static function fromRequest(Request $request): self
    {
        $payload = $request->toArray();
        return new self(
            $payload['name'] ?? '',
            $payload['description'] ?? null
        );
    }
}

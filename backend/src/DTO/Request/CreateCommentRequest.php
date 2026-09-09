<?php
declare(strict_types=1);

namespace App\DTO\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCommentRequest
{
    #[Assert\NotBlank]
    public readonly string $body;

    private function __construct(string $body)
    {
        $this->body = $body;
    }

    public static function fromRequest(Request $request): self
    {
        $payload = $request->toArray();
        return new self(
            $payload['body'] ?? ''
        );
    }
}

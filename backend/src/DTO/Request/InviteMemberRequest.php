<?php
declare(strict_types=1);

namespace App\DTO\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class InviteMemberRequest
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public readonly string $email;

    #[Assert\NotBlank]
    public readonly string $role;

    private function __construct(string $email, string $role)
    {
        $this->email = $email;
        $this->role = $role;
    }

    public static function fromRequest(Request $request): self
    {
        $payload = $request->toArray();
        return new self(
            $payload['email'] ?? '',
            $payload['role'] ?? 'MEMBER'
        );
    }
}

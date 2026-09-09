<?php
declare(strict_types=1);

namespace App\DTO\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class LoginRequest
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public readonly string $email;

    #[Assert\NotBlank]
    public readonly string $password;

    private function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public static function fromRequest(Request $request): self
    {
        $payload = $request->toArray();
        return new self(
            $payload['email'] ?? '',
            $payload['password'] ?? ''
        );
    }
}

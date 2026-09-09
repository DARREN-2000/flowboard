<?php
declare(strict_types=1);

namespace App\DTO\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterRequest
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public readonly string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8)]
    public readonly string $password;

    #[Assert\NotBlank]
    public readonly string $fullName;

    private function __construct(string $email, string $password, string $fullName)
    {
        $this->email = $email;
        $this->password = $password;
        $this->fullName = $fullName;
    }

    public static function fromRequest(Request $request): self
    {
        $payload = $request->toArray();
        return new self(
            $payload['email'] ?? '',
            $payload['password'] ?? '',
            $payload['fullName'] ?? ''
        );
    }
}

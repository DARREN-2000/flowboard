<?php
declare(strict_types=1);

namespace App\DTO\Response;

use App\Entity\User;

class UserResponse
{
    public readonly string $id;
    public readonly string $email;
    public readonly string $fullName;

    private function __construct(string $id, string $email, string $fullName)
    {
        $this->id = $id;
        $this->email = $email;
        $this->fullName = $fullName;
    }

    public static function fromEntity(User $user): self
    {
        return new self(
            (string) $user->getId(),
            $user->getEmail(),
            $user->getFullName()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'fullName' => $this->fullName,
        ];
    }
}

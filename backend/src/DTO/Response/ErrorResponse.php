<?php
declare(strict_types=1);

namespace App\DTO\Response;

class ErrorResponse
{
    public readonly string $message;
    public readonly array $errors;

    public function __construct(string $message, array $errors = [])
    {
        $this->message = $message;
        $this->errors = $errors;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'errors' => $this->errors,
        ];
    }
}

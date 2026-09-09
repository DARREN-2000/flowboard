<?php
declare(strict_types=1);

namespace App\DTO\Response;

use App\Entity\Comment;

class CommentResponse
{
    public readonly string $id;
    public readonly string $body;
    public readonly UserResponse $user;
    public readonly string $createdAt;

    private function __construct(string $id, string $body, UserResponse $user, string $createdAt)
    {
        $this->id = $id;
        $this->body = $body;
        $this->user = $user;
        $this->createdAt = $createdAt;
    }

    public static function fromEntity(Comment $comment): self
    {
        return new self(
            (string) $comment->getId(),
            $comment->getBody(),
            UserResponse::fromEntity($comment->getUser()),
            $comment->getCreatedAt()->format(\DateTimeInterface::ATOM)
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'user' => $this->user->toArray(),
            'createdAt' => $this->createdAt,
        ];
    }
}

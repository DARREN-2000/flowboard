<?php
declare(strict_types=1);

namespace App\DTO\Response;

use App\Entity\Workspace;

class WorkspaceDetailResponse
{
    public readonly string $id;
    public readonly string $name;
    public readonly string $slug;
    public readonly ?string $description;
    public readonly string $createdAt;

    private function __construct(string $id, string $name, string $slug, ?string $description, string $createdAt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->description = $description;
        $this->createdAt = $createdAt;
    }

    public static function fromEntity(Workspace $workspace): self
    {
        return new self(
            (string) $workspace->getId(),
            $workspace->getName(),
            $workspace->getSlug(),
            $workspace->getDescription(),
            $workspace->getCreatedAt()->format(\DateTimeInterface::ATOM)
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'createdAt' => $this->createdAt,
        ];
    }
}

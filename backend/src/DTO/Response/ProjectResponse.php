<?php
declare(strict_types=1);

namespace App\DTO\Response;

use App\Entity\Project;

class ProjectResponse
{
    public readonly string $id;
    public readonly string $name;
    public readonly string $slug;
    public readonly ?string $description;
    public readonly string $workspaceId;
    public readonly string $createdAt;

    private function __construct(string $id, string $name, string $slug, ?string $description, string $workspaceId, string $createdAt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->description = $description;
        $this->workspaceId = $workspaceId;
        $this->createdAt = $createdAt;
    }

    public static function fromEntity(Project $project): self
    {
        return new self(
            (string) $project->getId(),
            $project->getName(),
            $project->getSlug(),
            $project->getDescription(),
            (string) $project->getWorkspace()->getId(),
            $project->getCreatedAt()->format(\DateTimeInterface::ATOM)
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'workspaceId' => $this->workspaceId,
            'createdAt' => $this->createdAt,
        ];
    }
}

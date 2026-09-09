<?php
declare(strict_types=1);

namespace App\DTO\Response;

use App\Entity\Workspace;

class WorkspaceResponse
{
    public readonly string $id;
    public readonly string $name;
    public readonly string $slug;

    private function __construct(string $id, string $name, string $slug)
    {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
    }

    public static function fromEntity(Workspace $workspace): self
    {
        return new self(
            (string) $workspace->getId(),
            $workspace->getName(),
            $workspace->getSlug()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}

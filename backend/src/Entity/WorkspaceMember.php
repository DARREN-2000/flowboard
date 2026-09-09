<?php
declare(strict_types=1);

namespace App\Entity;

use App\Enum\WorkspaceRole;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'workspace_members')]
#[ORM\UniqueConstraint(name: 'workspace_user_idx', columns: ['workspace_id', 'user_id'])]
class WorkspaceMember
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Workspace::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Workspace $workspace;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(type: 'string', enumType: WorkspaceRole::class)]
    private WorkspaceRole $role;

    #[ORM\Column]
    private \DateTimeImmutable $joinedAt;

    public function __construct(Workspace $workspace, User $user, WorkspaceRole $role = WorkspaceRole::MEMBER)
    {
        $this->id = Uuid::v7();
        $this->workspace = $workspace;
        $this->user = $user;
        $this->role = $role;
        $this->joinedAt = new \DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getWorkspace(): Workspace
    {
        return $this->workspace;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getRole(): WorkspaceRole
    {
        return $this->role;
    }

    public function setRole(WorkspaceRole $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getJoinedAt(): \DateTimeImmutable
    {
        return $this->joinedAt;
    }
}

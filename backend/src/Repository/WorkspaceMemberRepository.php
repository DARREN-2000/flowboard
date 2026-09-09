<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\WorkspaceMember;
use App\Entity\User;
use App\Entity\Workspace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorkspaceMember>
 */
class WorkspaceMemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkspaceMember::class);
    }

    public function findByUser(User $user): array
    {
        return $this->findBy(['user' => $user]);
    }

    public function findByWorkspaceAndUser(Workspace $workspace, User $user): ?WorkspaceMember
    {
        return $this->findOneBy(['workspace' => $workspace, 'user' => $user]);
    }
}

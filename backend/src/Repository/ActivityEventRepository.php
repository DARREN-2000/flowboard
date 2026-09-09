<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\ActivityEvent;
use App\Entity\Project;
use App\Entity\Workspace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivityEvent>
 */
class ActivityEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityEvent::class);
    }

    public function findByWorkspace(Workspace $workspace, int $limit = 50): array
    {
        return $this->findBy(['workspace' => $workspace], ['createdAt' => 'DESC'], $limit);
    }

    public function findByProject(Project $project, int $limit = 50): array
    {
        return $this->findBy(['project' => $project], ['createdAt' => 'DESC'], $limit);
    }
}

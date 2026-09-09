<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project;
use App\Entity\Workspace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findByWorkspace(Workspace $workspace): array
    {
        return $this->findBy(['workspace' => $workspace], ['createdAt' => 'DESC']);
    }

    public function findByWorkspaceAndSlug(Workspace $workspace, string $slug): ?Project
    {
        return $this->findOneBy(['workspace' => $workspace, 'slug' => $slug]);
    }
}

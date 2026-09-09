<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project;
use App\Entity\Task;
use App\Enum\TaskStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findByProject(Project $project): array
    {
        return $this->findBy(['project' => $project], ['position' => 'ASC', 'createdAt' => 'DESC']);
    }

    public function findByStatus(Project $project, TaskStatus $status): array
    {
        return $this->findBy(['project' => $project, 'status' => $status], ['position' => 'ASC']);
    }
}

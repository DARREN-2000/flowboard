<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\AiSuggestion;
use App\Entity\Project;
use App\Enum\AiSuggestionStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AiSuggestion>
 */
class AiSuggestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AiSuggestion::class);
    }

    public function findPendingByProject(Project $project): array
    {
        return $this->findBy(['project' => $project, 'status' => AiSuggestionStatus::PENDING], ['createdAt' => 'DESC']);
    }
}

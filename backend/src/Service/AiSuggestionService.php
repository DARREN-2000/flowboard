<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\AiSuggestion;
use App\Entity\Project;
use App\Entity\User;
use App\Enum\AiSuggestionStatus;
use App\Enum\AiSuggestionType;
use Doctrine\ORM\EntityManagerInterface;

class AiSuggestionService
{
    private EntityManagerInterface $em;
    private MercurePublisher $mercurePublisher;

    public function __construct(EntityManagerInterface $em, MercurePublisher $mercurePublisher)
    {
        $this->em = $em;
        $this->mercurePublisher = $mercurePublisher;
    }

    public function createSuggestion(Project $project, User $user, AiSuggestionType $type, array $inputData): AiSuggestion
    {
        $suggestion = new AiSuggestion($project, $user, $type, $inputData);
        $this->em->persist($suggestion);
        $this->em->flush();

        return $suggestion;
    }

    public function resolveSuggestion(AiSuggestion $suggestion, AiSuggestionStatus $status): void
    {
        $suggestion->setStatus($status);
        $this->em->flush();

        $this->mercurePublisher->publish(
            sprintf('/projects/%s/ai', $suggestion->getProject()->getId()),
            [
                'id' => (string) $suggestion->getId(),
                'status' => $status->value
            ]
        );
    }
}

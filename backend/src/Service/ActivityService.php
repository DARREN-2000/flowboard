<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\ActivityEvent;
use App\Entity\Project;
use App\Entity\Task;
use App\Entity\User;
use App\Entity\Workspace;
use Doctrine\ORM\EntityManagerInterface;

class ActivityService
{
    private EntityManagerInterface $em;
    private MercurePublisher $mercurePublisher;

    public function __construct(EntityManagerInterface $em, MercurePublisher $mercurePublisher)
    {
        $this->em = $em;
        $this->mercurePublisher = $mercurePublisher;
    }

    public function logActivity(
        Workspace $workspace,
        User $user,
        string $eventType,
        array $metadata = [],
        ?Project $project = null,
        ?Task $task = null
    ): void {
        $event = new ActivityEvent($workspace, $user, $eventType, $metadata);
        
        if ($project) {
            $event->setProject($project);
        }
        
        if ($task) {
            $event->setTask($task);
        }
        
        $this->em->persist($event);
        $this->em->flush();

        $this->mercurePublisher->publish(
            sprintf('/workspaces/%s/activity', $workspace->getId()),
            [
                'id' => (string) $event->getId(),
                'type' => $eventType,
                'user' => [
                    'id' => (string) $user->getId(),
                    'fullName' => $user->getFullName(),
                ],
                'metadata' => $metadata,
                'createdAt' => $event->getCreatedAt()->format(\DateTimeInterface::ATOM)
            ]
        );
    }
}

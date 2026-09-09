<?php
declare(strict_types=1);

namespace App\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;

class ActivitySubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->logActivity('persisted', $args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->logActivity('updated', $args);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $this->logActivity('removed', $args);
    }

    private function logActivity(string $action, LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        $this->logger->info(sprintf('Entity %s was %s', get_class($entity), $action));
    }
}

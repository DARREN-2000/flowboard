<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'task_dependencies')]
#[ORM\UniqueConstraint(name: 'task_dependency_idx', columns: ['task_id', 'depends_on_task_id'])]
class TaskDependency
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Task::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Task $task;

    #[ORM\ManyToOne(targetEntity: Task::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Task $dependsOnTask;

    public function __construct(Task $task, Task $dependsOnTask)
    {
        $this->id = Uuid::v7();
        $this->task = $task;
        $this->dependsOnTask = $dependsOnTask;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function getDependsOnTask(): Task
    {
        return $this->dependsOnTask;
    }
}

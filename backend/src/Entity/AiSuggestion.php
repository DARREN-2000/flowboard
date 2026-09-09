<?php
declare(strict_types=1);

namespace App\Entity;

use App\Enum\AiSuggestionStatus;
use App\Enum\AiSuggestionType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'ai_suggestions')]
class AiSuggestion
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Project::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Project $project;

    #[ORM\ManyToOne(targetEntity: Task::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Task $task = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $requestedBy;

    #[ORM\Column(type: 'string', enumType: AiSuggestionType::class)]
    private AiSuggestionType $type;

    #[ORM\Column(type: 'string', enumType: AiSuggestionStatus::class)]
    private AiSuggestionStatus $status;

    #[ORM\Column(type: 'json')]
    private array $inputData = [];

    #[ORM\Column(type: 'json')]
    private array $outputData = [];

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $resolvedAt = null;

    public function __construct(Project $project, User $requestedBy, AiSuggestionType $type, array $inputData = [])
    {
        $this->id = Uuid::v7();
        $this->project = $project;
        $this->requestedBy = $requestedBy;
        $this->type = $type;
        $this->status = AiSuggestionStatus::PENDING;
        $this->inputData = $inputData;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;
        return $this;
    }

    public function getRequestedBy(): User
    {
        return $this->requestedBy;
    }

    public function getType(): AiSuggestionType
    {
        return $this->type;
    }

    public function getStatus(): AiSuggestionStatus
    {
        return $this->status;
    }

    public function setStatus(AiSuggestionStatus $status): self
    {
        $this->status = $status;
        if ($status !== AiSuggestionStatus::PENDING) {
            $this->resolvedAt = new \DateTimeImmutable();
        }
        return $this;
    }

    public function getInputData(): array
    {
        return $this->inputData;
    }

    public function getOutputData(): array
    {
        return $this->outputData;
    }

    public function setOutputData(array $outputData): self
    {
        $this->outputData = $outputData;
        return $this;
    }

    public function getResolvedAt(): ?\DateTimeImmutable
    {
        return $this->resolvedAt;
    }
}

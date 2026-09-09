<?php
declare(strict_types=1);

namespace App\DTO\Response;

use App\Entity\AiSuggestion;

class AiSuggestionResponse
{
    public readonly string $id;
    public readonly string $type;
    public readonly string $status;
    public readonly array $outputData;

    private function __construct(string $id, string $type, string $status, array $outputData)
    {
        $this->id = $id;
        $this->type = $type;
        $this->status = $status;
        $this->outputData = $outputData;
    }

    public static function fromEntity(AiSuggestion $suggestion): self
    {
        return new self(
            (string) $suggestion->getId(),
            $suggestion->getType()->value,
            $suggestion->getStatus()->value,
            $suggestion->getOutputData()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'status' => $this->status,
            'outputData' => $this->outputData,
        ];
    }
}

<?php
declare(strict_types=1);

namespace App\Message;

class GenerateTasksMessage
{
    private string $suggestionId;

    public function __construct(string $suggestionId)
    {
        $this->suggestionId = $suggestionId;
    }

    public function getSuggestionId(): string
    {
        return $this->suggestionId;
    }
}

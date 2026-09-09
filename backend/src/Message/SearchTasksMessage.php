<?php
declare(strict_types=1);

namespace App\Message;

class SearchTasksMessage
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

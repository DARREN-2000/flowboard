<?php
declare(strict_types=1);

namespace App\Enum;

enum AiSuggestionType: string
{
    case TASK_GENERATION = 'TASK_GENERATION';
    case DECOMPOSITION = 'DECOMPOSITION';
    case SUMMARY = 'SUMMARY';
    case SEARCH = 'SEARCH';
}

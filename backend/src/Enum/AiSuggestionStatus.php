<?php
declare(strict_types=1);

namespace App\Enum;

enum AiSuggestionStatus: string
{
    case PENDING = 'PENDING';
    case ACCEPTED = 'ACCEPTED';
    case REJECTED = 'REJECTED';
    case PARTIAL = 'PARTIAL';
}

<?php
declare(strict_types=1);

namespace App\Enum;

enum TaskStatus: string
{
    case BACKLOG = 'BACKLOG';
    case TODO = 'TODO';
    case IN_PROGRESS = 'IN_PROGRESS';
    case IN_REVIEW = 'IN_REVIEW';
    case DONE = 'DONE';
}

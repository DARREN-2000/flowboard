<?php
declare(strict_types=1);

namespace App\Enum;

enum WorkspaceRole: string
{
    case OWNER = 'OWNER';
    case ADMIN = 'ADMIN';
    case MEMBER = 'MEMBER';
    case VIEWER = 'VIEWER';
}

<?php
declare(strict_types=1);

namespace App\Enum;

enum TaskPriority: string
{
    case LOW = 'LOW';
    case MEDIUM = 'MEDIUM';
    case HIGH = 'HIGH';
    case CRITICAL = 'CRITICAL';

    /**
     * Returns all valid string values for use with Symfony Validator Choice constraint.
     *
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

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

    /**
     * Returns all valid string values for use with Symfony Validator Choice constraint.
     *
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Returns the allowed transitions from each status.
     * Used to validate that task moves follow a valid workflow.
     *
     * @return TaskStatus[]
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::BACKLOG => [self::TODO, self::IN_PROGRESS],
            self::TODO => [self::BACKLOG, self::IN_PROGRESS],
            self::IN_PROGRESS => [self::TODO, self::IN_REVIEW, self::DONE],
            self::IN_REVIEW => [self::IN_PROGRESS, self::DONE],
            self::DONE => [self::IN_PROGRESS, self::IN_REVIEW],
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->allowedTransitions(), true);
    }
}

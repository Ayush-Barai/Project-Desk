<?php

declare(strict_types=1);

namespace App\Enums;

enum TaskStatus: string
{
    case Backlog = 'Backlog';
    case Todo = 'Todo';
    case InProgress = 'InProgress';
    case InReview = 'InReview';
    case Done = 'Done';
    case Cancelled = 'Cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Backlog => 'Backlog',
            self::Todo => 'To Do',
            self::InProgress => 'In Progress',
            self::InReview => 'In Review',
            self::Done => 'Done',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Backlog => 'gray',
            self::Todo => 'blue',
            self::InProgress => 'indigo',
            self::InReview => 'purple',
            self::Done => 'green',
            self::Cancelled => 'red',
        };
    }
}

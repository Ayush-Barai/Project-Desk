<?php

declare(strict_types=1);

namespace App\Enums;

enum ProjectStatus: string
{
    case Planning = 'Planning';
    case Active = 'Active';
    case OnHold = 'OnHold';
    case Completed = 'Completed';
    case Archived = 'Archived';

    public function label(): string
    {
        return match ($this) {
            self::Planning => 'Planning',
            self::Active => 'Active',
            self::OnHold => 'On Hold',
            self::Completed => 'Completed',
            self::Archived => 'Archived',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Planning => 'gray',
            self::Active => 'blue',
            self::OnHold => 'yellow',
            self::Completed => 'green',
            self::Archived => 'dark',
        };
    }
}

<?php

namespace App\States\Progress;

class InProgress extends ProgressStatus
{
    public static string $name = 'in_progress';

    public static function label(): string
    {
        return 'In Progress';
    }

    public static function color(): string
    {
        return 'warning'; // For Filament badges
    }
}

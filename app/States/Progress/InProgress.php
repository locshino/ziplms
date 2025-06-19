<?php

namespace App\States\Progress;

class InProgress extends ProgressStatus
{
    public static string $name = 'in_progress';

    public function label(): string
    {
        return 'In Progress';
    }

    public function color(): string
    {
        return 'warning'; // For Filament badges
    }
}

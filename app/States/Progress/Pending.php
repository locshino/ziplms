<?php

namespace App\States\Progress;

class Pending extends ProgressStatus
{
    public static string $name = 'pending';

    public function label(): string
    {
        return 'Pending';
    }

    public function color(): string
    {
        return 'info'; // For Filament badges
    }
}

<?php

namespace App\States\Progress;

class Pending extends ProgressStatus
{
    public static string $name = 'pending';

    public static function label(): string
    {
        return 'Pending';
    }

    public static function color(): string
    {
        return 'info'; // For Filament badges
    }
}

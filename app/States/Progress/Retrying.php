<?php

namespace App\States\Progress;

class Retrying extends ProgressStatus
{
    public static string $name = 'retrying';

    public static function label(): string
    {
        return 'Retrying';
    }

    public static function color(): string
    {
        return 'warning'; // For Filament badges
    }
}

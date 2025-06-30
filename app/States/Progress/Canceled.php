<?php

namespace App\States\Progress;

class Canceled extends ProgressStatus
{
    public static string $name = 'canceled';

    public static function label(): string
    {
        return 'Canceled';
    }

    public static function color(): string
    {
        return 'secondary'; // For Filament badges
    }
}

<?php

namespace App\States\Progress;

class Failed extends ProgressStatus
{
    public static string $name = 'failed';

    public static function label(): string
    {
        return 'Failed';
    }

    public static function color(): string
    {
        return 'danger'; // For Filament badges
    }
}

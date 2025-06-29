<?php

namespace App\States\Progress;

class Done extends ProgressStatus
{
    public static string $name = 'done';

    public static function label(): string
    {
        return 'Done';
    }

    public static function color(): string
    {
        return 'success'; // For Filament badges
    }
}

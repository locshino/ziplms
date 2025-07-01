<?php

namespace App\States\Progress;

class DoneWithErrors extends ProgressStatus
{
    public static string $name = 'done_with_errors';

    public static function label(): string
    {
        return 'Done with Errors';
    }

    public static function color(): string
    {
        return 'warning'; // For Filament badges
    }
}

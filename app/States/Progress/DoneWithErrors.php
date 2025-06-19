<?php

namespace App\States\Progress;

class DoneWithErrors extends ProgressStatus
{
    public static string $name = 'done_with_errors';

    public function label(): string
    {
        return 'Done with Errors';
    }

    public function color(): string
    {
        return 'warning'; // For Filament badges
    }
}

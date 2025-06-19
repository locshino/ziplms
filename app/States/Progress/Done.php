<?php

namespace App\States\Progress;

class Done extends ProgressStatus
{
    public static string $name = 'done';

    public function label(): string
    {
        return 'Done';
    }

    public function color(): string
    {
        return 'success'; // For Filament badges
    }
}

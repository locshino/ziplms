<?php

namespace App\States\Progress;

class Failed extends ProgressStatus
{
    public static string $name = 'failed';

    public function label(): string
    {
        return 'Failed';
    }

    public function color(): string
    {
        return 'danger'; // For Filament badges
    }
}

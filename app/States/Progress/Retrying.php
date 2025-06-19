<?php

namespace App\States\Progress;

class Retrying extends ProgressStatus
{
    public static string $name = 'retrying';

    public function label(): string
    {
        return 'Retrying';
    }

    public function color(): string
    {
        return 'warning'; // For Filament badges
    }
}

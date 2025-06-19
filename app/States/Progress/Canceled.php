<?php

namespace App\States\Progress;

class Canceled extends ProgressStatus
{
    public static string $name = 'canceled';

    public function label(): string
    {
        return 'Canceled';
    }

    public function color(): string
    {
        return 'secondary'; // For Filament badges
    }
}

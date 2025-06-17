<?php

namespace App\States;

class Active extends Status
{
    public static string $name = 'active';

    public function label(): string
    {
        return 'Active';
    }

    public function color(): string
    {
        return 'success'; // For Filament badges
    }
}

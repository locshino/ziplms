<?php

namespace App\States;

class Active extends Status
{
    public static string $name = 'active';

    public static function label(): string
    {
        return 'Active';
    }

    public static function color(): string
    {
        return 'success'; // For Filament badges
    }
}

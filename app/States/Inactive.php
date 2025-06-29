<?php

namespace App\States;

class Inactive extends Status
{
    public static string $name = 'inactive';

    public static function label(): string
    {
        return 'Inactive';
    }

    public static function color(): string
    {
        return 'danger'; // For Filament badges
    }
}

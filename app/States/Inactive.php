<?php

namespace App\States;

class Inactive extends Status
{
    public static string $name = 'inactive';

    public function label(): string
    {
        return 'Inactive';
    }

    public function color(): string
    {
        return 'danger'; // For Filament badges
    }
}

<?php

namespace App\States\Location;

class Available extends LocationStatus
{
    public static string $name = 'available';

    public static function label(): string
    {
        return __('states_location_status.available.label');
    }

    public static function color(): string
    {
        return 'success';
    }

    public static function icon(): string
    {
        return 'heroicon-o-check-circle';
    }

    public static function description(): string
    {
        return __('states_location_status.available.description');
    }
}

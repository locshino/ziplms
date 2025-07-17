<?php

namespace App\States\Location;

class Planned extends LocationStatus
{
    public static string $name = 'planned';

    public static function label(): string
    {
        return __('states_location_status.planned.label');
    }

    public static function color(): string
    {
        return 'info';
    }

    public static function icon(): string
    {
        return 'heroicon-o-calendar-days';
    }

    public static function description(): string
    {
        return __('states_location_status.planned.description');
    }
}

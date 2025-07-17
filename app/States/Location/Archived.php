<?php

namespace App\States\Location;

class Archived extends LocationStatus
{
    public static string $name = 'archived';

    public static function label(): string
    {
        return __('states_location_status.archived.label');
    }

    public static function color(): string
    {
        return 'secondary';
    }

    public static function icon(): string
    {
        return 'heroicon-o-archive-box-x-mark';
    }

    public static function description(): string
    {
        return __('states_location_status.archived.description');
    }
}

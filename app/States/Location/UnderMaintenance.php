<?php

namespace App\States\Location;

class UnderMaintenance extends LocationStatus
{
    public static string $name = 'under_maintenance';

    public static function label(): string
    {
        return __('states_location_status.under_maintenance.label');
    }

    public static function color(): string
    {
        return 'warning';
    }

    public static function icon(): string
    {
        return 'heroicon-o-wrench-screwdriver';
    }

    public static function description(): string
    {
        return __('states_location_status.under_maintenance.description');
    }
}

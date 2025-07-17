<?php

namespace App\States;

class Postponed extends Status
{
    /**
     * @var string The static name of the state.
     */
    public static string $name = 'postponed';

    public static function label(): string
    {
        return __('states_status.postponed.label');
    }

    public static function color(): string
    {
        return 'gray';
    }

    public static function icon(): string
    {
        return 'heroicon-o-pause-circle';
    }

    public static function description(): string
    {
        // "The event has been delayed and will be rescheduled for a later time."
        return __('states_status.postponed.description');
    }
}

<?php

namespace App\States;

class InProgress extends Status
{
    /**
     * @var string The static name of the state.
     */
    public static string $name = 'in_progress';

    public static function label(): string
    {
        return __('states_status.in_progress.label');
    }

    public static function color(): string
    {
        return 'info';
    }

    public static function icon(): string
    {
        return 'heroicon-o-play-circle';
    }

    public static function description(): string
    {
        // "The scheduled event is currently happening (current time is between start_time and end_time)."
        return __('states_status.in_progress.description');
    }
}

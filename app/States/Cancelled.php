<?php

namespace App\States;

class Cancelled extends Status
{
    /**
     * @var string The static name of the state.
     */
    public static string $name = 'cancelled';

    public static function label(): string
    {
        return __('states_status.cancelled.label');
    }

    public static function color(): string
    {
        return 'danger';
    }

    public static function icon(): string
    {
        return 'heroicon-o-x-circle';
    }

    public static function description(): string
    {
        // "The event was cancelled before it could be completed."
        return __('states_status.cancelled.description');
    }
}

<?php

namespace App\States;

class Confirmed extends Status
{
    /**
     * @var string The static name of the state.
     */
    public static string $name = 'confirmed';

    public static function label(): string
    {
        return __('states_status.confirmed.label');
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
        // "Schedule has been approved and is officially scheduled to happen.
        // This is visible to all relevant users."
        return __('states_status.confirmed.description');
    }
}

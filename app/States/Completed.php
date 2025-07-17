<?php

namespace App\States;

class Completed extends Status
{
    /**
     * @var string The static name of the state.
     */
    public static string $name = 'completed';

    public static function label(): string
    {
        return __('states_status.completed.label');
    }

    public static function color(): string
    {
        return 'primary';
    }

    public static function icon(): string
    {
        return 'heroicon-o-check-badge';
    }

    public static function description(): string
    {
        // "The scheduled event has finished as planned."
        return __('states_status.completed.description');
    }
}

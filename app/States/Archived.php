<?php

namespace App\States;

class Archived extends Status
{
    /**
     * @var string The static name of the state.
     */
    public static string $name = 'archived';

    public static function label(): string
    {
        return __('states_status.archived.label');
    }

    public static function color(): string
    {
        return 'secondary';
    }

    public static function icon(): string
    {
        return 'heroicon-o-archive-box';
    }

    public static function description(): string
    {
        // "The event is old and has been moved to archives. It is not shown in regular lists."
        return __('states_status.archived.description');
    }
}

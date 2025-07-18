<?php

namespace App\States;

class Pending extends Status
{
    /**
     * @var string The static name of the state.
     */
    public static string $name = 'pending';

    /**
     * Provides a human-readable label for the state.
     */
    public static function label(): string
    {
        return __('states_status.pending.label');
    }

    /**
     * Returns a color associated with the state for UI elements.
     */
    public static function color(): string
    {
        return 'warning';
    }

    /**
     * Returns an icon associated with the state for UI elements.
     */
    public static function icon(): string
    {
        return 'heroicon-o-clock';
    }

    /**
     * Provides a detailed description of the state's purpose.
     */
    public static function description(): string
    {
        // "Schedule has been created but is awaiting confirmation or final details.
        // It is not yet visible to end-users."
        return __('states_status.pending.description');
    }
}

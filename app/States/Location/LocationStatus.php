<?php

namespace App\States\Location;

use App\States\Base\State;
use Spatie\ModelStates\StateConfig;

abstract class LocationStatus extends State
{
    /**
     * The lang file for translations.
     */
    public static string $langFile = 'states_location_status';

    /**
     * Configure the state machine.
     */
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Planned::class)
            ->allowTransition(Planned::class, Available::class)
            ->allowTransition(Available::class, UnderMaintenance::class)
            ->allowTransition(UnderMaintenance::class, Available::class)
            ->allowTransition(Available::class, Archived::class)
            ->allowTransition(UnderMaintenance::class, Archived::class)
            ->allowTransition(Archived::class, Available::class);
    }

    /**
     * Get all possible states.
     */
    public static function getStates(): array
    {
        return [
            Planned::class,
            Available::class,
            UnderMaintenance::class,
            Archived::class,
        ];
    }
}

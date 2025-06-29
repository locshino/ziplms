<?php

namespace App\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class Status extends State
{
    // Define methods here that are available for all states.
    abstract public static function label(): string;

    abstract public static function color(): string; // Example of a shared abstract method

    // Use the config() method to define transitions and the default state
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Active::class)
            ->allowTransition(Inactive::class, Active::class)
            ->allowTransition(Active::class, Inactive::class);
    }
}

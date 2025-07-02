<?php

namespace App\States;

use App\States\Active;
use App\States\Inactive;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class Status extends State
{
    // Define methods here that are available for all states.
    abstract public function label(): string;

    abstract public function color(): string; // Example of a shared abstract method

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Active::class)
            ->allowTransition(Inactive::class, Active::class)
            ->allowTransition(Active::class, Inactive::class);
    }
}

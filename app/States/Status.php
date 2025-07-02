<?php

namespace App\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class Status extends State
{
    abstract public function color(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->allowTransition(Started::class, Completed::class);
    }
}

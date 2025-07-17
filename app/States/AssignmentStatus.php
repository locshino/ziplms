<?php

// app/States/AssignmentStatus.php

namespace App\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class AssignmentStatus extends State
{
    abstract public static function color(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Draft::class)
            ->allowTransition(Draft::class, Published::class)
            ->allowTransition(Published::class, Closed::class);
    }
}

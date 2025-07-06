<?php

namespace App\States;

use Spatie\ModelStates\StateConfig;

abstract class Status extends Base\State
{
    public static string $langFile = 'states_status';

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Active::class)
            ->allowTransition(Inactive::class, Active::class)
            ->allowTransition(Active::class, Inactive::class);
    }

    public static function getStates(): array
    {
        return [
            Active::class,
            Inactive::class,
        ];
    }
}

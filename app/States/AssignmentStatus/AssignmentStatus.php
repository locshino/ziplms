<?php

// app/States/AssignmentStatus.php

namespace App\States\AssignmentStatus;

use Illuminate\Support\Str;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class AssignmentStatus extends State
{
    public static function key(): string
    {
        return Str::snake(class_basename(static::class));
    }

    public static function label(): string
    {
        return __('assignment_statuses.'.static::key().'.label');
    }

    public static function color(): string
    {
        return __('assignment_statuses.'.static::key().'.color');
    }

    public static function icon(): string
    {
        return __('assignment_statuses.'.static::key().'.icon');
    }

    public static function description(): string
    {
        return __('assignment_statuses.'.static::key().'.description');
    }

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Draft::class)
            ->allowTransition(Draft::class, Published::class)
            ->allowTransition(Published::class, Closed::class)
            ->allowTransition(Published::class, Cancelled::class)
            ->allowTransition(Draft::class, Cancelled::class)
            ->allowTransition(Closed::class, Draft::class);
    }
}

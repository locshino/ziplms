<?php

namespace App\States\SubmissionStatus;

use Illuminate\Support\Str;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class SubmissionStatus extends State
{
    public static function key(): string
    {
        return Str::snake(class_basename(static::class));
    }

    public static function label(): string
    {
        return __('submission_status.'.static::key().'.label');
    }

    public static function color(): string
    {
        return __('submission_status.'.static::key().'.color');
    }

    public static function icon(): string
    {
        return __('submission_status.'.static::key().'.icon');
    }

    public static function description(): string
    {
        return __('submission_status.'.static::key().'.description');
    }

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, InProgress::class)
            ->allowTransition(InProgress::class, Active::class)
            ->allowTransition(Active::class, Submitted::class)
            ->allowTransition(Submitted::class, Graded::class)
            ->allowTransition(Graded::class, Completed::class)
            ->allowTransition(Completed::class, Archived::class)
            ->allowTransition(Active::class, Inactive::class)
            ->allowTransition(Inactive::class, Pending::class)
            ->allowTransition(Pending::class, Postponed::class);
    }
}

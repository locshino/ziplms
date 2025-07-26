<?php

namespace App\States\SubmissionStatus;

use App\States\Base\State;
use Spatie\ModelStates\StateConfig;

abstract class SubmissionStatus extends State
{
    public static string $langFile = 'submission_statuses';

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

    public static function getStates(): array
    {
        return [
            Pending::class,
            InProgress::class,
            Active::class,
            Submitted::class,
            Graded::class,
            Completed::class,
            Archived::class,
            Inactive::class,
            Postponed::class,
        ];
    }
}

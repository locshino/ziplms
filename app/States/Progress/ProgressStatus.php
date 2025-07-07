<?php

namespace App\States\Progress;

use App\States\Base\State; // Use the base State class
use Spatie\ModelStates\StateConfig;

// ProgressStatus now extends our custom base State, inheriting all the Filament interface logic.
abstract class ProgressStatus extends State
{
    public static string $langFile = 'states_progress-status';

    /**
     * Get all concrete state classes for this group.
     * This is the implementation for the abstract getStates() in the base class.
     */
    public static function getStates(): array
    {
        return [
            Pending::class,
            InProgress::class,
            Done::class,
            DoneWithErrors::class,
            Failed::class,
            Retrying::class,
            Canceled::class,
        ];
    }

    /**
     * Configure the state machine for progress statuses.
     */
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, InProgress::class)
            ->allowTransition(InProgress::class, Done::class)
            ->allowTransition(InProgress::class, DoneWithErrors::class)
            ->allowTransition(InProgress::class, Failed::class)
            ->allowTransition(DoneWithErrors::class, Failed::class)
            ->allowTransition(Failed::class, Retrying::class)
            ->allowTransition(Retrying::class, InProgress::class)
            ->allowTransition(Pending::class, Canceled::class)
            ->allowTransition(InProgress::class, Canceled::class);
    }
}

<?php

namespace App\States\Progress;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class ProgressStatus extends State
{
    // Define methods here that are available for all states.
    abstract public function label(): string;

    abstract public function color(): string; // Example of a shared abstract method

    // Use the config() method to define transitions and the default state
    public static function config(): StateConfig
    {
        return parent::config()
            // Default state when a batch is created
            ->default(Pending::class)

            // Transitions from Pending
            ->allowTransition(Pending::class, InProgress::class)
            ->allowTransition(Pending::class, Canceled::class)

            // Transitions from InProgress
            ->allowTransition(InProgress::class, Done::class)
            ->allowTransition(InProgress::class, DoneWithErrors::class)
            ->allowTransition(InProgress::class, Failed::class)
            ->allowTransition(InProgress::class, Canceled::class)

            // Transitions from Failed (for retrying)
            ->allowTransition(Failed::class, Retrying::class)
            ->allowTransition(Retrying::class, InProgress::class);
        // Done, DoneWithErrors, Canceled are considered final states in this basic setup.
        // If DoneWithErrors could lead to other states (e.g., manual review, partial retry),
        // those transitions would be added here.
    }
}

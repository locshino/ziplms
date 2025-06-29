<?php

namespace App\States\Progress;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class ProgressStatus extends State
{
    // Define methods here that are available for all states.
    abstract public static function label(): string;

    abstract public static function color(): string; // Example of a shared abstract method

    /**
     * Configure the state machine.
     */
    public static function config(): StateConfig
    {
        return parent::config()
            // --- Initial State ---
            // A batch always starts as Pending.
            ->default(Pending::class)

            // --- Normal Workflow ---
            // A pending job can be picked up by a worker.
            ->allowTransition(Pending::class, InProgress::class)
            // The job is running.
            ->allowTransition(InProgress::class, Done::class)
            // The job finished, but some rows had validation errors.
            ->allowTransition(InProgress::class, DoneWithErrors::class)

            // --- Failure & Retry Workflow ---
            // A job can fail while it's in progress.
            ->allowTransition(InProgress::class, Failed::class)
            // A job that completed with non-critical errors can still fail due to a critical error later (e.g., notification fails).
            ->allowTransition(DoneWithErrors::class, Failed::class) // <-- ADDED THIS LINE TO FIX THE ERROR
            // An admin can decide to retry a failed job.
            ->allowTransition(Failed::class, Retrying::class)
            // The system picks up the retrying job and puts it back in progress.
            ->allowTransition(Retrying::class, InProgress::class)

            // --- Cancellation Workflow ---
            // A user can cancel a job that is waiting or already in progress.
            ->allowTransition(Pending::class, Canceled::class)
            ->allowTransition(InProgress::class, Canceled::class);

        // NOTE: Done, DoneWithErrors, Canceled, and Failed are considered final states unless a transition
        // from them (like from Failed to Retrying) is explicitly defined.
    }
}

<?php

namespace App\States;

use Spatie\ModelStates\StateConfig;

abstract class Status extends Base\State
{
    public static string $langFile = 'states_status';

    /**
     * Controls the transition logic.
     * If true, any state can transition to any other state.
     * If false, a structured transition flow is enforced.
     */
    public static bool $isAllowAllTransitions = true;

    public static string $defaultStateClass = Pending::class;

    /**
     * Configures the states and transitions for the Status state machine.
     */
    public static function config(): StateConfig
    {
        $config = parent::config()
            ->default(static::$defaultStateClass);

        $allStates = static::$isAllowAllTransitions
            ? static::getStates()
            : self::getStates();

        foreach ($allStates as $toState) {
            $config->allowTransition($allStates, $toState);
        }

        return $config;
    }

    /**
     * Get the list of all concrete state classes.
     * Used for validation and generating options.
     *
     * @return array<int, class-string<self>>
     */
    public static function getStates(): array
    {
        return [
            Pending::class,
            Active::class,
            Inactive::class,
            InProgress::class,
            Completed::class,
            Cancelled::class,
            Postponed::class,
            Archived::class,
        ];
    }
}

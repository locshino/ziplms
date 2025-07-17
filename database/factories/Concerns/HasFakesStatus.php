<?php

namespace Database\Factories\Concerns;

trait HasFakesStatus
{
    /**
     * Generate a random status class from a model state class (extended from your App\States\Status).
     *
     * @param  class-string  $statusClass  Class that defines getStates(): array of class names
     * @return class-string
     */
    protected function fakeStatus(string $statusClass = \App\States\Status::class): string
    {
        if (! method_exists($statusClass, 'getStates')) {
            throw new \InvalidArgumentException("Class {$statusClass} must implement a static getStates() method.");
        }

        $states = $statusClass::getStates();

        return $states[array_rand($states)]::$name;
    }
}

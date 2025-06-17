<?php

namespace Database\Factories\Concerns;

use App\States;

trait HasFakesStatus
{
    /**
     * Generate a random status for the model.
     */
    protected function fakeStatus(int $activePercentage = 90): string
    {
        return fake()->boolean($activePercentage)
            ? States\Active::class
            : States\Inactive::class;
    }
}

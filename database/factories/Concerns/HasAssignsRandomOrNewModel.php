<?php

namespace Database\Factories\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasAssignsRandomOrNewModel
{
    /**
     * Get the value of a column from a random existing model,
     * or create a new one using its factory.
     *
     * @param  string  $modelClass  Fully qualified class name of the Eloquent model.
     * @param  string  $column      Column to retrieve (default 'id').
     * @return mixed
     */
    protected function assignRandomOrNewModel(string $modelClass, string $column = 'id'): mixed
    {
        if (!class_exists($modelClass) || !is_subclass_of($modelClass, Model::class)) {
            throw new \InvalidArgumentException("{$modelClass} is not a valid Eloquent model.");
        }

        $model = $modelClass::inRandomOrder()->first();

        return $model?->{$column} ?? $modelClass::factory()->create()->{$column};
    }
}

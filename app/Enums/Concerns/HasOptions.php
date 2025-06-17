<?php

namespace App\Enums\Concerns;

trait HasOptions
{
    /**
     * Get the options for the enum.
     */
    public static function options(): array
    {
        return collect(static::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }

    /**
     * Get the label for the enum value.
     */
    abstract public function label(): string;
}

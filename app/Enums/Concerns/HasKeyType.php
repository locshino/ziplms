<?php

namespace App\Enums\Concerns;

trait HasKeyType
{
    /**
     * Returns the fully qualified class name of the enum.
     * This is useful for providing a unique type for Spatie Tags.
     *
     * @return string The fully qualified class name (e.g., 'App\Enums\LocationType').
     *
     * @example $query->withAnyTags($tags, LocationType::key());
     */
    public static function key(): string
    {
        return self::class;
    }
}

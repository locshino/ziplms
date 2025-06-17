<?php

namespace App\Enums\Concerns;

trait HasEnumValues
{
    /**
     * Get all case values as an array.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}

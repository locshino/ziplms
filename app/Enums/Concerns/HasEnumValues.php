<?php

namespace App\Enums\Concerns;

trait HasEnumValues
{
    /**
     * Get all case values as an array, with an option to exclude specific cases.
     *
     * @example
     * // Get all roles: ['admin', 'manager', 'teacher', 'student']
     * RoleEnum::values();
     *
     * // Get all roles except Admin: ['manager', 'teacher', 'student']
     * RoleEnum::values(RoleEnum::Admin);
     *
     * // Get all roles except Admin and Manager: ['teacher', 'student']
     * RoleEnum::values(RoleEnum::Admin, RoleEnum::Manager);
     *
     * // Get all roles except 'student': ['admin', 'manager', 'teacher']
     * RoleEnum::values('student');
     *
     * @param  self|string  ...$except  The cases or their string values to exclude.
     * @return array<int, string>
     */
    public static function values(self|string ...$except): array
    {
        // Get all values from the enum cases efficiently.
        $allValues = array_column(self::cases(), 'value');

        // If no cases are to be excluded, return all values immediately.
        if (empty($except)) {
            return $allValues;
        }

        // Normalize the excluded items to an array of their string values.
        // This allows passing either an enum case (e.g., RoleEnum::Admin) or a string (e.g., 'admin').
        $exceptValues = array_map(
            fn ($e) => $e instanceof \BackedEnum ? $e->value : $e,
            $except
        );

        // Return the difference and re-index the array.
        return array_values(array_diff($allValues, $exceptValues));
    }
}

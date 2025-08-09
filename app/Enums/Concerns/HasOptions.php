<?php

namespace App\Enums\Concerns;

/**
 * Trait HasOptions
 * 
 * Provides utility methods for working with PHP enums, offering various ways
 * to extract and format enum cases for use in forms, dropdowns, and other UI components.
 * 
 * @package App\Enums\Concerns
 */
trait HasOptions
{
    /**
     * Get all enum cases as an associative array with values as keys and names as values.
     * 
     * This method is particularly useful for creating dropdown options where you need
     * the enum value as the option value and the enum name as the display text.
     * 
     * @return array<string|int, string> Array with enum values as keys and names as values
     * 
     * @example
     * // For an enum with cases: ACTIVE('active'), INACTIVE('inactive')
     * // Returns: ['active' => 'ACTIVE', 'inactive' => 'INACTIVE']
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->name])
            ->toArray();
    }

    /**
     * Get all enum case names as a simple array.
     * 
     * Returns only the constant names (e.g., 'ACTIVE', 'INACTIVE') without their values.
     * Useful when you need to work with enum names programmatically.
     * 
     * @return array<int, string> Array of enum case names
     * 
     * @example
     * // For an enum with cases: ACTIVE('active'), INACTIVE('inactive')
     * // Returns: ['ACTIVE', 'INACTIVE']
     */
    public static function names(): array
    {
        return collect(self::cases())
            ->pluck('name')
            ->toArray();
    }

    /**
     * Get all enum case values as a simple array.
     * 
     * Returns only the enum values (e.g., 'active', 'inactive') without their names.
     * Useful for validation, database queries, or when you need just the values.
     * 
     * @return array<int, string|int> Array of enum case values
     * 
     * @example
     * // For an enum with cases: ACTIVE('active'), INACTIVE('inactive')
     * // Returns: ['active', 'inactive']
     */
    public static function values(): array
    {
        return collect(self::cases())
            ->pluck('value')
            ->toArray();
    }

    /**
     * Get all enum cases as an associative array with names as keys and values as values.
     * 
     * This is the inverse of the options() method, providing enum names as keys
     * and their corresponding values as array values.
     * 
     * @return array<string, string|int> Array with enum names as keys and values as values
     * 
     * @example
     * // For an enum with cases: ACTIVE('active'), INACTIVE('inactive')
     * // Returns: ['ACTIVE' => 'active', 'INACTIVE' => 'inactive']
     */
    public static function toArray(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->name => $case->value])
            ->toArray();
    }

    /**
     * Get the localized label for this enum case.
     * 
     * This method retrieves the translated label from language files based on
     * the enum class name and case value. It follows the naming convention:
     * 'enums_permissions_{snake_case_class_name}.{case_value}'
     * 
     * @return string The localized label for this enum case
     * 
     * @example
     * // For PermissionContextEnum::ALL
     * // Looks up: __('enums_permissions_permission_context_enum.all')
     */
    public function getLabel(): string
    {
        $className = class_basename(static::class);
        $snakeCaseClassName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className));
        $translationKey = "enums_permissions_{$snakeCaseClassName}.{$this->value}";
        
        return __($translationKey);
    }

    /**
     * Get all enum cases with their localized labels.
     * 
     * Returns an associative array with enum values as keys and their
     * corresponding localized labels as values.
     * 
     * @return array<string|int, string> Array with enum values as keys and labels as values
     * 
     * @example
     * // Returns: ['all' => 'All Items', 'owner' => 'Owner Only', ...]
     */
    public static function optionsWithLabels(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()])
            ->toArray();
    }
}

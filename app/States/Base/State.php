<?php

namespace App\States\Base;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Spatie\ModelStates\State as ModelStates;
use Spatie\ModelStates\StateConfig;

abstract class State extends ModelStates implements HasColor, HasDescription, HasIcon, HasLabel
{
    /**
     * The name of the state, used for storage.
     * This must be defined in each concrete state class.
     */
    public static string $name;

    public static string $langFile = 'states_default';

    // --- Abstract "Contract" Methods ---
    // These must be implemented by each concrete state class (e.g., Active, Pending).
    abstract public static function label(): string;

    abstract public static function color(): string;

    abstract public static function icon(): string;

    abstract public static function description(): string;

    // --- Instance Methods for Filament Contracts ---
    // These methods fulfill the requirements of Filament's interfaces.
    public function getLabel(): ?string
    {
        return static::label() ?? static::defaultLabel();
    }

    public function getColor(): string|array|null
    {
        return static::color() ?? static::defaultColor();
    }

    public function getIcon(): ?string
    {
        return static::icon() ?? static::defaultIcon();
    }

    public function getDescription(): ?string
    {
        return static::description() ?? static::defaultDescription();
    }

    // --- Enum-like Behavior ---

    /**
     * Define the list of all concrete states for this group.
     * This method must be implemented by each abstract state group (e.g., Status, ProgressStatus).
     * This emulates Enum::cases().
     *
     * @return array<int, class-string<self>>
     */
    abstract public static function getStates(): array;

    /**
     * Attempt to create an instance of the state from a given name or instance.
     * Returns null if the name does not match any known state or if the value is invalid.
     *
     * @param  string|self|null  $value  The name of the state or an instance of the state.
     * @return static|null Returns an instance of the state if successful, null otherwise.
     */
    public static function tryFrom(string|self|null $value): ?self
    {
        if ($value instanceof self) {
            return $value;
        }

        if (is_string($value) && isset(static::getStates()[$value])) {
            return new static($value);
        }

        return null;
    }

    /**
     * Get all states as a key-value pair array for Filament Select options.
     * This emulates the behavior of HasOptions trait for Enums.
     * The key will be the state's static $name property.
     *
     * @return array<string, string>
     */
    public static function getOptions(): array
    {
        return collect(static::getStates())
            ->mapWithKeys(fn (string $stateClass) => [
                // Use the static $name property as the key
                $stateClass::$name => $stateClass::label(),
            ])
            ->toArray();
    }

    // --- Default Values ---
    public static function defaultLabel(): string
    {
        return __(static::getLangFile().'.default.label');
    }

    public static function defaultColor(): string
    {
        return 'secondary';
    }

    public static function defaultIcon(): string
    {
        return 'heroicon-o-question-mark-circle';
    }

    public static function defaultDescription(): string
    {
        return __(static::getLangFile().'.default.description');
    }

    // --- Language File Handling ---
    public static function getLangFile(): string
    {
        return self::$langFile;
    }

    // --- Serialization Mapping ---
    /**
     * Map the stored name of a state to its corresponding class.
     * This is required by spatie/laravel-model-states for serialization.
     *
     * @return array<string, class-string<self>>
     */
    public static function map(): array
    {
        return collect(self::getStates())
            ->mapWithKeys(fn (string $stateClass) => [
                $stateClass::$name => $stateClass,
            ])
            ->toArray();
    }

    /**
     * Returns the base configuration for the state.
     *
     * This method overrides the parent class's config method to provide
     * a specific configuration for the base state.
     *
     * @return StateConfig The base configuration for the state.
     */
    public static function baseConfig(): StateConfig
    {
        return parent::config();
    }
}

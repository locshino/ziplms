<?php

namespace App\States\Base;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Spatie\ModelStates\State as ModelStates;

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
     * Get all states as a key-value pair array for Filament Select options.
     * This emulates the behavior of HasOptions trait for Enums.
     * The key will be the state's static $name property.
     *
     * @return array<string, string>
     */
    public static function getOptions(): array
    {
        return collect(static::getStates())
            ->mapWithKeys(fn(string $stateClass) => [
                // Use the static $name property as the key
                $stateClass::$name => $stateClass::label(),
            ])
            ->toArray();
    }

    public static function defaultLabel(): string
    {
        return __(static::getLangFile() . '.default.label');
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
        return __(static::getLangFile() . '.default.description');
    }

    public static function getLangFile(): string
    {
        return self::$langFile;
    }
}

<?php

namespace App\Enums\Concerns;

use Filament\Forms\Components\MorphToSelect\Type;

/**
 * Provides functionality to generate types for a MorphToSelect component.
 *
 * This trait assumes the Enum using it has the following methods:
 * - cases()
 * - getModelClass(): string
 * - getLabel(): string
 * - getTitleColumn(): string
 */
trait HasMorphToSelectTypes
{
    /**
     * Generate the array of Type objects for a MorphToSelect component.
     * This centralizes the logic for building the types.
     */
    public static function getMorphToSelectTypes(): array
    {
        return collect(self::cases())
            ->map(
                fn (self $type) => Type::make($type->getModelClass())
                    ->label($type->getLabel())
                    ->titleAttribute($type->getTitleColumn())
            )
            ->all();
    }
}

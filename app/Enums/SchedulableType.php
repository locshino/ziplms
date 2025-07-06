<?php

namespace App\Enums;

use App\Models\ClassesMajor;
use App\Models\Course;
use App\Models\Lecture;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum SchedulableType: string implements HasColor, HasDescription, HasIcon, HasLabel
{
    use Concerns\HasEnumValues, Concerns\HasMorphToSelectTypes;

    case Course = 'course';
    case Lecture = 'lecture';
    case ClassesMajor = 'classes_major';

    /**
     * Get the displayable label for the enum case.
     */
    public function getLabel(): string
    {
        return __("enums_schedulable-type.{$this->value}.label");
    }

    /**
     * Get the displayable description for the enum case.
     */
    public function getDescription(): string
    {
        return __("enums_schedulable-type.{$this->value}.description");
    }

    /**
     * Get the icon associated with the enum case.
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::Course => 'heroicon-o-book-open',
            self::Lecture => 'heroicon-o-presentation-chart-line',
            self::ClassesMajor => 'heroicon-o-academic-cap',
            default => 'heroicon-o-question-mark-circle',
        };
    }

    /**
     * Get the color associated with the enum case.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Course => 'primary',
            self::Lecture => 'success',
            self::ClassesMajor => 'warning',
            default => 'gray',
        };
    }

    /**
     * Get the corresponding model class for the type.
     */
    public function getModelClass(): string
    {
        return match ($this) {
            self::Course => Course::class,
            self::Lecture => Lecture::class,
            self::ClassesMajor => ClassesMajor::class,
            // A default is not strictly needed here if all cases are handled,
            // but can be useful for robustness.
        };
    }

    /**
     * Get the title attribute/column for the model.
     */
    public function getTitleColumn(): string
    {
        return match ($this) {
            self::Lecture => 'title', // Lecture model uses 'title' column
            self::Course, self::ClassesMajor => 'name', // Other models use 'name'
            default => 'name', // Default fallback
        };
    }
}

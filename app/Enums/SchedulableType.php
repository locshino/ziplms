<?php

namespace App\Enums;

use App\Models\ClassesMajor;
use App\Models\Course;
use App\Models\Lecture;

enum SchedulableType: string implements Contracts\HasFilamentEnumStyle
{
    use Concerns\HasEnumValues, Concerns\HasMorphToSelectTypes;

    case Course = 'course';
    case Lecture = 'lecture';
    case ClassesMajor = 'classes_major';

    /**
     * The single source of truth for model mapping.
     */
    private static function modelMap(): array
    {
        return [
            self::Course->value => Course::class,
            self::Lecture->value => Lecture::class,
            self::ClassesMajor->value => ClassesMajor::class,
        ];
    }

    /**
     * Get the corresponding model class for the enum case.
     */
    public function getModelClass(): string
    {
        return self::modelMap()[$this->value];
    }

    /**
     * Get the enum case from a model class string.
     */
    public static function fromModelClass(string $modelClass): self
    {
        $caseValue = array_search($modelClass, self::modelMap());

        // The from() method is a built-in feature of PHP Backed Enums.
        return self::from($caseValue);
    }

    // ... các hàm getLabel, getDescription, getIcon, getColor, getTitleColumn không thay đổi ...

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

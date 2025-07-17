<?php

namespace App\Enums;

enum LocationType: string implements Contracts\HasFilamentEnumStyle
{
    use Concerns\HasEnumValues,
        Concerns\HasKeyType,
        Concerns\HasOptions;

    /**
     * A physical, in-person location (e.g., a classroom, a hall).
     */
    case Physical = 'physical';

    /**
     * A live, synchronous virtual session (e.g., Zoom, Teams).
     */
    case VirtualLive = 'virtual_live';

    /**
     * An asynchronous online resource (e.g., pre-recorded videos, articles).
     */
    case AsynchronousOnline = 'asynchronous_online';

    /**
     * An event that occurs both physically and virtually at the same time.
     */
    case Hybrid = 'hybrid';

    /**
     * The location has not been decided yet.
     */
    case TBD = 'tbd';

    public function label(): string
    {
        return self::getLabel();
    }

    /**
     * Get the displayable label for the enum case.
     */
    public function getLabel(): string
    {
        // Using the translation helper for multilingual support.
        return __("enums_location-type.{$this->value}.label");
    }

    /**
     * Get the displayable description for the enum case.
     */
    public function getDescription(): string
    {
        // Using the translation helper for multilingual support.
        return __("enums_location-type.{$this->value}.description");
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Physical => 'heroicon-o-building-office-2',
            self::VirtualLive => 'heroicon-o-video-camera',
            self::AsynchronousOnline => 'heroicon-o-globe-alt',
            self::Hybrid => 'heroicon-o-users',
            self::TBD => 'heroicon-o-question-mark-circle',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Physical => 'warning',
            self::VirtualLive => 'primary',
            self::AsynchronousOnline => 'success',
            self::Hybrid => 'info',
            self::TBD => 'secondary',
        };
    }
}

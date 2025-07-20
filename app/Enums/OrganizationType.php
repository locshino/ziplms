<?php

namespace App\Enums;

enum OrganizationType: string
{
    use Concerns\HasEnumValues,
        Concerns\HasKeyType,
        Concerns\HasOptions;

    case HighSchool = 'high_school';
    case College = 'college';
    case University = 'university';
    case TrainingCenter = 'training_center';

    public function label(): string
    {
        return match ($this) {
            self::HighSchool => 'High School',
            self::College => 'College',
            self::University => 'University',
            self::TrainingCenter => 'Training Center',
            default => 'Unknown Type',
        };
    }

    public static function key(): string
    {
        return 'organization-type';
    }
}

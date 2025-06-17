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
            self::HighSchool => 'Trường cấp 3',
            self::College => 'Trường cao đẳng',
            self::University => 'Trường đại học',
            self::TrainingCenter => 'Trung tâm đào tạo',
            default => 'Unknown Type',
        };
    }

    public static function key(): string
    {
        return 'organization-type';
    }
}

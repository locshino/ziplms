<?php

namespace App\Enums;

enum CourseStaffRoleType: string
{
    use Concerns\HasEnumValues,
        Concerns\HasKeyType,
        Concerns\HasOptions;

    case Instructor = 'instructor';
    case TeachingAssistant = 'teaching_assistant';
    case Marker = 'marker';

    public function label(): string
    {
        return match ($this) {
            self::Instructor => 'Giảng viên',
            self::TeachingAssistant => 'Trợ giảng',
            self::Marker => 'Người chấm bài',
            default => 'Unknown Type',
        };
    }

    public static function key(): string
    {
        return 'course-staff-role-type';
    }
}

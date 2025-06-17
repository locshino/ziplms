<?php

namespace App\Enums;

enum ClassesMajorType: string
{
    use Concerns\HasEnumValues,
        Concerns\HasKeyType,
        Concerns\HasOptions;

    case ClassRoom = 'class_room';
    case Major = 'major';
    case Department = 'department';
    case GradeLevel = 'grade_level';

    public function label(): string
    {
        return match ($this) {
            self::ClassRoom => 'Lớp học',
            self::Major => 'Chuyên ngành',
            self::Department => 'Khoa',
            self::GradeLevel => 'Khối lớp',
            default => 'Unknown Type',
        };
    }

    public static function key(): string
    {
        return 'classes-major-type';
    }
}

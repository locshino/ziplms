<?php

namespace App\Enums;

enum EnrollmentType: string
{
    use Concerns\HasEnumValues,
        Concerns\HasKeyType,
        Concerns\HasOptions;

    case Student = 'student';
    case HomeroomTeacher = 'homeroom_teacher';
    case SubjectTeacher = 'subject_teacher';
    case Dean = 'dean';
    case Member = 'member';

    public function label(): string
    {
        return match ($this) {
            self::Student => 'Học sinh',
            self::HomeroomTeacher => 'Giáo viên chủ nhiệm',
            self::SubjectTeacher => 'Giáo viên bộ môn',
            self::Dean => 'Trưởng khoa',
            self::Member => 'Thành viên',
            default => 'Unknown Type',
        };
    }

    public static function key(): string
    {
        return 'enrollment-type';
    }
}

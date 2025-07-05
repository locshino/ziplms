<?php

namespace App\Enums;

use App\Models\ClassesMajor;
use App\Models\Course;
use App\Models\Lecture;

enum SchedulableType: string
{
    use Concerns\HasEnumValues,
        Concerns\HasOptions;

    case Course = 'course';
    case Lecture = 'lecture';
    case ClassesMajor = 'classes_major';

    public function label(): string
    {
        return match ($this) {
            self::Course => 'Khóa học (Course)',
            self::Lecture => 'Bài giảng (Lecture)',
            self::ClassesMajor => 'Lớp/Chuyên ngành',
        };
    }

    /**
     * Lấy ra class Model tương ứng với từng loại.
     */
    public function getModelClass(): string
    {
        return match ($this) {
            self::Course => Course::class,
            self::Lecture => Lecture::class,
            self::ClassesMajor => ClassesMajor::class,
        };
    }
}

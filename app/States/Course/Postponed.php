<?php

namespace App\States\Course;

class Postponed extends CourseStatus
{
    public static string $name = 'postponed';

    public static function label(): string
    {
        return __('states_course_status.postponed.label');
    }

    public static function color(): string
    {
        return 'secondary';
    }

    public static function icon(): string
    {
        return 'heroicon-o-pause-circle';
    }

    public static function description(): string
    {
        return __('states_course_status.postponed.description');
    }
}
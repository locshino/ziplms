<?php

namespace App\States\Course;

class Completed extends CourseStatus
{
    public static string $name = 'completed';

    public static function label(): string
    {
        return __('states_course_status.completed.label');
    }

    public static function color(): string
    {
        return 'success';
    }

    public static function icon(): string
    {
        return 'heroicon-o-check-badge';
    }

    public static function description(): string
    {
        return __('states_course_status.completed.description');
    }
}

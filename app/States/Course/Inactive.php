<?php

namespace App\States\Course;

class Inactive extends CourseStatus
{
    public static string $name = 'inactive';

    public static function label(): string
    {
        return __('states_course_status.inactive.label');
    }

    public static function color(): string
    {
        return 'secondary';
    }

    public static function icon(): string
    {
        return 'heroicon-o-no-symbol';
    }

    public static function description(): string
    {
        return __('states_course_status.inactive.description');
    }
}

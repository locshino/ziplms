<?php

namespace App\States\Course;

class Archived extends CourseStatus
{
    public static string $name = 'archived';

    public static function label(): string
    {
        return __('states_course_status.archived.label');
    }

    public static function color(): string
    {
        return 'light';
    }

    public static function icon(): string
    {
        return 'heroicon-o-archive-box';
    }

    public static function description(): string
    {
        return __('states_course_status.archived.description');
    }
}

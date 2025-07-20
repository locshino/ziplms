<?php

namespace App\States\Course;

class Pending extends CourseStatus
{
    public static string $name = 'pending';

    public static function label(): string
    {
        return __('states_course_status.pending.label');
    }

    public static function color(): string
    {
        return 'warning';
    }

    public static function icon(): string
    {
        return 'heroicon-o-clock';
    }

    public static function description(): string
    {
        return __('states_course_status.pending.description');
    }
}
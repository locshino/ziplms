<?php

namespace App\States\Course;

class InProgress extends CourseStatus
{
    public static string $name = 'in_progress';

    public static function label(): string
    {
        return __('states_course_status.in_progress.label');
    }

    public static function color(): string
    {
        return 'primary';
    }

    public static function icon(): string
    {
        return 'heroicon-o-arrow-path';
    }

    public static function description(): string
    {
        return __('states_course_status.in_progress.description');
    }
}
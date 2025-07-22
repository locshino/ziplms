<?php

namespace App\States\Course;

class Active extends CourseStatus
{
    public static string $name = 'active';

    public static function label(): string
    {
        return __('states_status.active.label');
    }

    public static function color(): string
    {
        return 'success';
    }

    public static function icon(): string
    {
        return 'heroicon-o-check-circle';
    }

    public static function description(): string
    {
        return __('states_status.active.description');
    }
}

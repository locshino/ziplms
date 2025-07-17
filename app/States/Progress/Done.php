<?php

namespace App\States\Progress;

class Done extends ProgressStatus
{
    public static string $name = 'done';

    public static function label(): string
    {
        return __('states_progress-status.done.label');
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
        return __('states_progress-status.done.description');
    }
}

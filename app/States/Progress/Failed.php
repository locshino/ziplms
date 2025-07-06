<?php

namespace App\States\Progress;

class Failed extends ProgressStatus
{
    public static string $name = 'failed';

    public static function label(): string
    {
        return __('states_progress-status.failed.label');
    }

    public static function color(): string
    {
        return 'danger';
    }

    public static function icon(): string
    {
        return 'heroicon-o-x-circle';
    }

    public static function description(): string
    {
        return __('states_progress-status.failed.description');
    }
}

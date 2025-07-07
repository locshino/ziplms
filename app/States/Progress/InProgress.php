<?php

namespace App\States\Progress;

class InProgress extends ProgressStatus
{
    public static string $name = 'in_progress';

    public static function label(): string
    {
        return __('states_progress-status.in_progress.label');
    }

    public static function color(): string
    {
        return 'warning';
    }

    public static function icon(): string
    {
        return 'heroicon-o-arrow-path';
    }

    public static function description(): string
    {
        return __('states_progress-status.in_progress.description');
    }
}

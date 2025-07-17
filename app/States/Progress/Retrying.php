<?php

namespace App\States\Progress;

class Retrying extends ProgressStatus
{
    public static string $name = 'retrying';

    public static function label(): string
    {
        return __('states_progress-status.retrying.label');
    }

    public static function color(): string
    {
        return 'warning';
    }

    public static function icon(): string
    {
        return 'heroicon-o-arrow-uturn-left';
    }

    public static function description(): string
    {
        return __('states_progress-status.retrying.description');
    }
}

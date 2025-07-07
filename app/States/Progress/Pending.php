<?php

namespace App\States\Progress;

class Pending extends ProgressStatus
{
    public static string $name = 'pending';

    public static function label(): string
    {
        return __('states_progress-status.pending.label');
    }

    public static function color(): string
    {
        return 'info';
    }

    public static function icon(): string
    {
        return 'heroicon-o-clock';
    }

    public static function description(): string
    {
        return __('states_progress-status.pending.description');
    }
}

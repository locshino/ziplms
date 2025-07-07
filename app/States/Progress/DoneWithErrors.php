<?php

namespace App\States\Progress;

class DoneWithErrors extends ProgressStatus
{
    public static string $name = 'done_with_errors';

    public static function label(): string
    {
        return __('states_progress-status.done_with_errors.label');
    }

    public static function color(): string
    {
        return 'warning';
    }

    public static function icon(): string
    {
        return 'heroicon-o-exclamation-triangle';
    }

    public static function description(): string
    {
        return __('states_progress-status.done_with_errors.description');
    }
}

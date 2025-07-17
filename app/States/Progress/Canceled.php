<?php

namespace App\States\Progress;

class Canceled extends ProgressStatus
{
    public static string $name = 'canceled';

    public static function label(): string
    {
        return __('states_progress-status.canceled.label');
    }

    public static function color(): string
    {
        return 'gray';
    }

    public static function icon(): string
    {
        return 'heroicon-o-minus-circle';
    }

    public static function description(): string
    {
        return __('states_progress-status.canceled.description');
    }
}

<?php

namespace App\States\SubmissionStatus;

class Active extends SubmissionStatus
{
    public static string $name = 'active';

    public static function label(): string
    {
        return __('submission_statuses.active.label');
    }

    public static function description(): string
    {
        return __('submission_statuses.active.description');
    }

    public static function color(): string
    {
        return __('submission_statuses.active.color');
    }

    public static function icon(): string
    {
        return 'heroicon-o-play';
    }
}

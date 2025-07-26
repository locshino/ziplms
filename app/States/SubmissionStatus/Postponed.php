<?php

namespace App\States\SubmissionStatus;

class Postponed extends SubmissionStatus
{
    public static string $name = 'postponed';

    public static function label(): string
    {
        return __('submission_statuses.postponed.label');
    }

    public static function description(): string
    {
        return __('submission_statuses.postponed.description');
    }

    public static function color(): string
    {
        return __('submission_statuses.postponed.color');
    }

    public static function icon(): string
    {
        return 'heroicon-o-calendar';
    }
}

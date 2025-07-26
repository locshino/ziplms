<?php

namespace App\States\SubmissionStatus;

class Inactive extends SubmissionStatus
{
    public static string $name = 'inactive';

    public static function label(): string
    {
        return __('submission_statuses.inactive.label');
    }

    public static function description(): string
    {
        return __('submission_statuses.inactive.description');
    }

    public static function color(): string
    {
        return __('submission_statuses.inactive.color');
    }

    public static function icon(): string
    {
        return 'heroicon-o-pause-circle';
    }
}

<?php

namespace App\States\SubmissionStatus;

class Completed extends SubmissionStatus
{
    public static string $name = 'completed';

    public static function label(): string
    {
        return __('submission_statuses.completed.label');
    }

    public static function description(): string
    {
        return __('submission_statuses.completed.description');
    }

    public static function color(): string
    {
        return __('submission_statuses.completed.color');
    }

    public static function icon(): string
    {
        return 'heroicon-o-check';
    }
}

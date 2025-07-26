<?php

namespace App\States\SubmissionStatus;

class InProgress extends SubmissionStatus
{
    public static string $name = 'in_progress';

    public static function label(): string
    {
        return __('submission_statuses.in_progress.label');
    }

    public static function description(): string
    {
        return __('submission_statuses.in_progress.description');
    }

    public static function color(): string
    {
        return __('submission_statuses.in_progress.color');
    }

    public static function icon(): string
    {
        return 'heroicon-o-play';
    }
}

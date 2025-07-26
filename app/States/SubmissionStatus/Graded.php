<?php

namespace App\States\SubmissionStatus;

class Graded extends SubmissionStatus
{
    public static string $name = 'graded';

    public static function label(): string
    {
        return __('submission_statuses.graded.label');
    }

    public static function description(): string
    {
        return __('submission_statuses.graded.description');
    }

    public static function color(): string
    {
        return __('submission_statuses.graded.color');
    }

    public static function icon(): string
    {
        return 'heroicon-o-check-circle';
    }
}

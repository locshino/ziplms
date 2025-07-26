<?php

namespace App\States\SubmissionStatus;

class Submitted extends SubmissionStatus
{
    public static string $name = 'submitted';

    public static function label(): string
    {
        return __('submission_statuses.submitted.label');
    }

    public static function description(): string
    {
        return __('submission_statuses.submitted.description');
    }

    public static function color(): string
    {
        return __('submission_statuses.submitted.color');
    }

    public static function icon(): string
    {
        return 'heroicon-o-arrow-up-tray';
    }
}

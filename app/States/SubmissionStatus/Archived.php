<?php

namespace App\States\SubmissionStatus;

class Archived extends SubmissionStatus
{
    public static string $name = 'archived';

    public static function label(): string
    {
        return __('submission_statuses.archived.label');
    }

    public static function description(): string
    {
        return __('submission_statuses.archived.description');
    }

    public static function color(): string
    {
        return __('submission_statuses.archived.color');
    }

    public static function icon(): string
    {
        return 'heroicon-o-archive-box';
    }
}

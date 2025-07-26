<?php

// app/States/SubmissionStatus/Pending.php

namespace App\States\SubmissionStatus;

class Pending extends SubmissionStatus
{
    public static string $name = 'pending';

    public static function label(): string
    {
        return __('submission_statuses.pending.label');
    }

    public static function description(): string
    {
        return __('submission_statuses.pending.description');
    }

    public static function color(): string
    {
        return __('submission_statuses.pending.color');
    }

    public static function icon(): string
    {
        return 'heroicon-o-clock';
    }
}

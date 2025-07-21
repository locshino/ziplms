<?php



namespace App\States\SubmissionStatus;

class Submitted extends SubmissionStatus
{
    public static $name = 'submitted';

    public static function color(): string
    {
        return 'warning';
    }
}

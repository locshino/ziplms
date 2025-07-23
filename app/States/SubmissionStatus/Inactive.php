<?php

namespace App\States\SubmissionStatus;

class Inactive extends SubmissionStatus
{
    public static $name = 'inactive';

    public static function color(): string
    {
        return 'danger';
    }
}

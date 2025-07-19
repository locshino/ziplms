<?php

namespace App\States\SubmissionStatus;

class Active extends SubmissionStatus
{
    public static $name = 'active';

    public static function color(): string
    {
        return 'primary';
    }
}

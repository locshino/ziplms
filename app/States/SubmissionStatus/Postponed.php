<?php

namespace App\States\SubmissionStatus;

class Postponed extends SubmissionStatus
{
    public static string $name = 'postponed';


    public static function color(): string
    {
        return 'warning'; 
    }
}

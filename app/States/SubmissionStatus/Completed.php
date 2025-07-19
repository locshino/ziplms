<?php

namespace App\States\SubmissionStatus;

class Completed extends SubmissionStatus
{
    public static $name = 'completed';


    public static function color(): string
    {
        return 'success'; 
    }
}

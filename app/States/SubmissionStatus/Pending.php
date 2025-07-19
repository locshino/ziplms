<?php
// app/States/SubmissionStatus/Pending.php

namespace App\States\SubmissionStatus;

class Pending extends SubmissionStatus
{
    public static $name = 'pending';

  
    public static function color(): string
    {
        return 'gray'; 
    }
}

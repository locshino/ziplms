<?php
// app/States/SubmissionStatus/Archived.php

namespace App\States\SubmissionStatus;

class Archived extends SubmissionStatus
{
    public static $name = 'archived';

   
    public static function color(): string
    {
        return 'gray'; 
    }
}

<?php

namespace App\States\AssignmentStatus;

class Cancelled extends AssignmentStatus
{
    public static $name = 'cancelled';

    public static function color(): string
    {
        return 'yellow';
    }
}

<?php

namespace App\States\AssignmentStatus;

class Draft extends AssignmentStatus
{
    public static $name = 'draft';

    public static function color(): string
    {
        return 'gray';
    }
}

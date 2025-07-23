<?php

namespace App\States\AssignmentStatus;

class Published extends AssignmentStatus
{
    public static $name = 'published';

    public static function color(): string
    {
        return 'green';
    }
}

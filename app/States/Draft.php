<?php

// app/States/Draft.php

namespace App\States;

class Draft extends AssignmentStatus
{
    public static $name = 'draft';

    public static function color(): string
    {
        return 'gray';
    }
}

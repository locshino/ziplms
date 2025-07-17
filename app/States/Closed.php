<?php

// app/States/Closed.php

namespace App\States;

class Closed extends AssignmentStatus
{
    public static $name = 'closed';

    public static function color(): string
    {
        return 'red';
    }
}

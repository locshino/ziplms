<?php

// app/States/Published.php

namespace App\States;

class Published extends AssignmentStatus
{
    public static $name = 'published';

    public static function color(): string
    {
        return 'green';
    }
}

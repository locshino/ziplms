<?php



namespace App\States\AssignmentStatus;

class Closed extends AssignmentStatus
{
    public static $name = 'closed';

    public static function color(): string
    {
        return 'red';
    }
}

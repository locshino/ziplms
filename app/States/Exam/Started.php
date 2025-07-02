<?php

namespace App\States\Exam;

use App\States\Exam\Status;

class Started extends Status
{
    public static string $name = 'started';

    public function color(): string
    {
        return 'warning';
    }
}

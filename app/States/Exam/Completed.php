<?php

namespace App\States\Exam;

use App\States\Exam\Status;

class Completed extends Status
{
    public static string $name = 'completed';

    public function color(): string
    {
        return 'success';
    }
}

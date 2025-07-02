<?php

namespace App\States\Exam;

class Started extends Status
{
    public static string $name = 'started';

    public function color(): string
    {
        return 'warning';
    }
}

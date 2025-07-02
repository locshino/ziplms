<?php

namespace App\States\Exam;

class Completed extends Status
{
    public static string $name = 'completed';

    public function color(): string
    {
        return 'success';
    }
}

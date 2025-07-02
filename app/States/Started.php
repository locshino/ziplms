<?php

namespace App\States;

class Started extends Status
{
    public static string $name = 'started';

    public function color(): string
    {
        return 'warning';
    }
}

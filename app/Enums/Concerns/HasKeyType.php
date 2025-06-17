<?php

namespace App\Enums\Concerns;

trait HasKeyType
{
    /**
     * Get the key type for the enum.
     */
    abstract public static function key(): string;
}

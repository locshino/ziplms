<?php

namespace App\States;

class Inactive extends Status
{
    public static string $name = 'inactive';

    public static function label(): string
    {
        return __('states_status.inactive.label');
    }

    public static function color(): string
    {
        return 'danger';
    }

    public static function icon(): string
    {
        return 'heroicon-o-x-circle';
    }

    public static function description(): string
    {
        return __('states_status.inactive.description');
    }
}

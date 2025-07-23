<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class OrganizationSettings extends Settings
{
    public ?string $address;

    public ?string $phone_number;

    public ?string $contact_email;

    public ?string $website;

    public bool $enable_public_courses;

    public static function group(): string
    {
        return 'organization';
    }
}

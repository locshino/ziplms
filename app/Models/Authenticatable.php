<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as BaseAuthenticatable;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class Authenticatable extends BaseAuthenticatable
{
    use HasRoles, TwoFactorAuthenticatable;
}

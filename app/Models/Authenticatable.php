<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as BaseAuthenticatable;
use Spatie\Permission\Traits\HasRoles;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;

class Authenticatable extends BaseAuthenticatable
{
    use HasRoles, TwoFactorAuthenticatable;
}

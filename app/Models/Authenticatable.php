<?php

namespace App\Models;

use App\Enums\System\RoleSystem;
use App\Libs\Roles\RoleHelper;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as BaseAuthenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;
use Spatie\LaravelPasskeys\Models\Concerns\HasPasskeys;
use Spatie\Permission\Traits\HasRoles;
use Stephenjude\FilamentTwoFactorAuthentication\TwoFactorAuthenticatable;
use Yebor974\Filament\RenewPassword\Contracts\RenewPasswordContract;
use Yebor974\Filament\RenewPassword\Traits\RenewPassword;

abstract class Authenticatable extends BaseAuthenticatable implements AuditableContract, HasPasskeys, RenewPasswordContract
{
    use Auditable,
        AuthenticationLoggable,
        HasFactory,
        HasRoles,
        HasUuids,
        Notifiable,
        RenewPassword,
        SoftDeletes,
        TwoFactorAuthenticatable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canImpersonate()
    {
        return RoleHelper::isSuperAdmin();
    }

    public function canBeImpersonated()
    {
        return ! $this->hasRole(RoleSystem::SUPER_ADMIN->value);
    }
}

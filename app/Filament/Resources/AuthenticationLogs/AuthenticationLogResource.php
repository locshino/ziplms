<?php

namespace App\Filament\Resources\AuthenticationLogs;

use App\Models\AuthenticationLog;
use Filament\Support\Icons\Heroicon;
use Tapp\FilamentAuthenticationLog\Resources\AuthenticationLogResource as FilamentAuthenticationLogResource;

class AuthenticationLogResource extends FilamentAuthenticationLogResource
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?string $model = AuthenticationLog::class;

    public static function getNavigationGroup(): ?string
    {
        return 'Logs';
    }
}

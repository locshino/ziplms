<?php

namespace App\Filament\Resources\AuthenticationLogs;

use App\Filament\Resources\AuthenticationLogs\AuthenticationLogResource\Pages;
use App\Filament\Resources\AuthenticationLogs\AuthenticationLogResource\RelationManagers;
use App\Models\AuthenticationLog;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tapp\FilamentAuthenticationLog\Resources\AuthenticationLogResource as FilamentAuthenticationLogResource;

class AuthenticationLogResource extends FilamentAuthenticationLogResource
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;
    protected static ?string $model = AuthenticationLog::class;

    public static function getNavigationGroup(): ?string
    {
        return "Logs";
    }
}

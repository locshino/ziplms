<?php

namespace App\Filament\Resources\MailLogs;

use App\Models\MailLog;
use Filament\Support\Icons\Heroicon;
use Tapp\FilamentMailLog\Resources\MailLogResource as FilamentMailLogResource;

class MailLogResource extends FilamentMailLogResource
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;
    protected static ?string $model = MailLog::class;

    public static function getNavigationGroup(): ?string
    {
        return "Logs";
    }
}

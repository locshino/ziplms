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
        return __('resource_mail_log.resource.navigation_group');
    }

    public static function getNavigationLabel(): string
    {
        return __('resource_mail_log.resource.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('resource_mail_log.resource.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource_mail_log.resource.plural_label');
    }
}

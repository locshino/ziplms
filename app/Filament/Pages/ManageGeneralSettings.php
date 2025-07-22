<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ManageGeneralSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.manage-general-settings';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return 'General Settings';
    }
}

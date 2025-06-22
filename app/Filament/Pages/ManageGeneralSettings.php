<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageGeneralSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = GeneralSettings::class;

    protected static ?string $navigationGroup = 'Cài đặt Hệ thống';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Define the form fields that match your settings properties.
                Forms\Components\TextInput::make('site_name')
                    ->label('Tên ứng dụng')
                    ->required(),

                Forms\Components\TextInput::make('support_email')
                    ->label('Email Hỗ trợ')
                    ->email()
                    ->required(),

                Forms\Components\ColorPicker::make('theme_color')
                    ->label('Màu chủ đạo'),

                Forms\Components\Toggle::make('site_active')
                    ->label('Kích hoạt Website')
                    ->helperText('Tắt để bật chế độ bảo trì.'),

                // Example for logo upload (requires medialibrary plugin)
                Forms\Components\SpatieMediaLibraryFileUpload::make('logo_path')
                    ->label('Logo')
                    ->collection('logos') // This will store the file in the 'logos' media collection
                    ->image(),

                Forms\Components\SpatieMediaLibraryFileUpload::make('favicon_path')
                    ->label('Favicon')
                    ->collection('favicons')
                    ->image(),
            ]);
    }

    // This method will HIDE the page from navigation until setup is complete
    // public static function canAccess(): bool
    // {
    //     return app(GeneralSettings::class)->is_setup_complete;
    // }
}

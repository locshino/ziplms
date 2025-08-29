<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\MimeType;
use App\Enums\Status\UserStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SpatieMediaLibraryFileUpload::make('avatar')
                    ->collection('avatar')
                    ->image()
                    ->multiple(false)
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '1:1',
                    ])
                    ->maxSize(2048)
                    ->acceptedFileTypes(MimeType::images())
                    ->label(__('resource_user.form.fields.avatar'))
                    ->helperText(__('resource_user.form.help_text.avatar'))
                    ->columnSpanFull(),
                TextInput::make('name')
                    ->label(__('resource_user.form.fields.name'))
                    ->required(),
                TextInput::make('email')
                    ->label(__('resource_user.form.fields.email'))
                    ->email()
                    ->required(),
                Select::make('roles')
                    ->label(__('resource_user.form.fields.roles'))
                    ->relationship('roles', 'name')
                    ->options(Role::all()->pluck('name', 'name'))
                    ->multiple()
                    ->preload()
                    ->searchable(),
                Select::make('status')
                    ->label(__('resource_user.form.fields.status'))
                    ->options(UserStatus::class)
                    ->required(),
                Toggle::make('force_renew_password')
                    ->label(__('resource_user.form.fields.force_renew_password'))
                    ->visible(fn () => config('ziplms.plugins.renew_password.enabled') && (config('ziplms.plugins.renew_password.force_renew_password') == false))
                    ->required(),
            ]);
    }
}

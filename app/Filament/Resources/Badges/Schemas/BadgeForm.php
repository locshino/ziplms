<?php

namespace App\Filament\Resources\Badges\Schemas;

use App\Enums\Status\BadgeStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BadgeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label(__('resource_badge.form.fields.title'))
                    ->required(),
                TextInput::make('slug')
                    ->label(__('resource_badge.form.fields.slug'))
                    ->required(),
                Textarea::make('description')
                    ->label(__('resource_badge.form.fields.description'))
                    ->columnSpanFull(),
                Select::make('status')
                    ->label(__('resource_badge.form.fields.status'))
                    ->options(BadgeStatus::class)
                    ->required(),
            ]);
    }
}

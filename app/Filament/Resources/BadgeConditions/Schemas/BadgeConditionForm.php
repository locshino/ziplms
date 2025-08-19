<?php

namespace App\Filament\Resources\BadgeConditions\Schemas;

use App\Enums\Status\BadgeConditionStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BadgeConditionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('condition_type')
                    ->required(),
                Textarea::make('condition_data')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(BadgeConditionStatus::class)
                    ->required(),
            ]);
    }
}

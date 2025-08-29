<?php

namespace App\Filament\Resources\BadgeConditions\Schemas;

use App\Enums\Status\BadgeConditionStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BadgeConditionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label(__('resource_badge_condition.form.fields.title'))
                    ->required(),
                Textarea::make('description')
                    ->label(__('resource_badge_condition.form.fields.description'))
                    ->columnSpanFull(),
                TextInput::make('condition_type')
                    ->label(__('resource_badge_condition.form.fields.condition_type'))
                    ->required(),
                Textarea::make('condition_data')
                    ->label(__('resource_badge_condition.form.fields.condition_data'))
                    ->columnSpanFull(),
                Select::make('status')
                    ->label(__('resource_badge_condition.form.fields.status'))
                    ->options(BadgeConditionStatus::class)
                    ->required(),
            ]);
    }
}

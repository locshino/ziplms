<?php

namespace App\Filament\Resources\Assignments\Schemas;

use App\Enums\Status\AssignmentStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AssignmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('max_points')
                    ->required()
                    ->numeric()
                    ->default(10),
                TextInput::make('max_attempts')
                    ->numeric(),
                Select::make('status')
                    ->options(AssignmentStatus::class)
                    ->required(),
            ]);
    }
}

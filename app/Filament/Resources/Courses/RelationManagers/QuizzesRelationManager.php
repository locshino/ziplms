<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use App\Filament\Resources\Quizzes\QuizResource;
use Filament\Actions\CreateAction;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuizzesRelationManager extends RelationManager
{
    protected static string $relationship = 'quizzes';

    // protected static ?string $relatedResource = QuizResource::class;

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Title')
                    ->columnSpanFull()
                    ->disabled()
                    ->required(),
                DateTimePicker::make('start_at')
                    ->label('Start At')
                    ->required(),
                DateTimePicker::make('end_at')
                    ->label('End At')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->copyable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('max_attempts')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_single_session')
                    ->boolean(),
                TextColumn::make('time_limit_minutes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('pivot.start_at')
                    ->label('Start At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('pivot.end_at')
                    ->label('End At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                AttachAction::make()
                    ->schema(fn(AttachAction $action): array => [
                        $action->getRecordSelect(),
                        DateTimePicker::make('start_at')
                            ->before('end_at')
                            ->required(),
                        DateTimePicker::make('end_at')
                            ->after('start_at')
                            ->required(),
                    ])
                    ->multiple()
            ])
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
            ])->toolbarActions([
                BulkActionGroup::make([
                    // ...
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}

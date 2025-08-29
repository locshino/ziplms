<?php

namespace App\Filament\Resources\Quizzes\RelationManagers;

use App\Enums\Status\QuestionStatus;
use App\Filament\Resources\Questions\QuestionResource;
use App\Filament\Resources\Questions\Tables\QuestionsTable;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ModalTableSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    // protected static ?string $relatedResource = QuestionResource::class;

    public function form(Schema $forms): Schema
    {
        return $forms
            ->schema([
                TextInput::make('title')
                    ->columnSpanFull()
                    ->label('Title')
                    ->disabled()
                    ->required(),
                TextInput::make('points')
                    ->label('Points')
                    ->numeric()
                    ->minValue(0)
                    ->required(),
                Select::make('status')
                    ->options(QuestionStatus::class)
                    ->disabled()
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
                TextColumn::make('points')
                    ->label('Points')
                    ->sortable(),
                TextColumn::make('status'),
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
                CreateAction::make(),
                AttachAction::make()
                    // ->multiple()
                    ->schema(fn (AttachAction $action) => [
                        $action->getRecordSelect(),
                        ModalTableSelect::make('recordId')
                            ->label('Question')
                            ->relationship('quizzes', 'title')
                            ->tableConfiguration(QuestionsTable::class),
                        TextInput::make('points')
                            ->label('Points')
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ]),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->options(QuestionStatus::class),
            ])
            ->recordActions([
                // ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}

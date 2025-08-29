<?php

namespace App\Filament\Resources\AnswerChoices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class AnswerChoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('resource_answer_choice.table.columns.id'))
                    ->copyable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('question.title')
                    ->label(__('resource_answer_choice.table.columns.question.title'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->label(__('resource_answer_choice.table.columns.title'))
                    ->searchable(),
                ToggleColumn::make('is_correct')
                    ->label(__('resource_answer_choice.table.columns.is_correct')),
                TextColumn::make('created_at')
                    ->label(__('resource_answer_choice.table.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('resource_answer_choice.table.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('resource_answer_choice.table.columns.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultGroup('question.title')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->schema([
                        \Filament\Forms\Components\ModalTableSelect::make('question_id')
                            ->relationship('question', 'title')
                            ->tableConfiguration(\App\Filament\Resources\Questions\Tables\QuestionsTable::class)
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('title')
                            ->label(__('resource_answer_choice.table.columns.title'))
                            ->required(),
                        \Filament\Forms\Components\Textarea::make('description')
                            ->label(__('resource_answer_choice.form.fields.description')),
                        \Filament\Forms\Components\Toggle::make('is_correct')
                            ->label(__('resource_answer_choice.table.columns.is_correct')),
                    ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}

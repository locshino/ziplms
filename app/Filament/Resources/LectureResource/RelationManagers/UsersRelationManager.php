<?php

namespace App\Filament\Resources\LectureResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public function form(Form $form): Form
    {
        $statusOptions = [
            'not_started' => __('lecture-resource.relation_manager.status.not_started'),
            'in_progress' => __('lecture-resource.relation_manager.status.in_progress'),
            'completed' => __('lecture-resource.relation_manager.status.completed'),
        ];

        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->options($statusOptions)
                    ->default('not_started')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        $statusOptions = [
            'not_started' => __('lecture-resource.relation_manager.status.not_started'),
            'in_progress' => __('lecture-resource.relation_manager.status.in_progress'),
            'completed' => __('lecture-resource.relation_manager.status.completed'),
        ];

        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('lecture-resource.relation_manager.table.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('lecture-resource.relation_manager.table.email'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('lecture-resource.relation_manager.table.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'not_started' => 'gray',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        default => 'danger', // Default case for unexpected status
                    })
                    ->formatStateUsing(fn (string $state): string => $statusOptions[$state] ?? $state),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label(__('lecture-resource.relation_manager.table.completed_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\Select::make('status')
                            ->options($statusOptions)
                            ->default('not_started')
                            ->required(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('lecture-resource.relation_manager.actions.edit_progress')),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}

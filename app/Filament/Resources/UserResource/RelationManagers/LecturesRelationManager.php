<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class LecturesRelationManager extends RelationManager
{
    protected static string $relationship = 'lectures';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('user-resource.relation_manager.tab_title');
    }

    public function form(Form $form): Form
    {
        $statusOptions = [
            'not_started' => __('user-resource.relation_manager.status.not_started'),
            'in_progress' => __('user-resource.relation_manager.status.in_progress'),
            'completed' => __('user-resource.relation_manager.status.completed'),
        ];

        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->label(__('user-resource.relation_manager.table.status'))
                    ->options($statusOptions)
                    ->default('not_started')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        $statusOptions = [
            'not_started' => __('user-resource.relation_manager.status.not_started'),
            'in_progress' => __('user-resource.relation_manager.status.in_progress'),
            'completed' => __('user-resource.relation_manager.status.completed'),
        ];

        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('user-resource.relation_manager.table.title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('user-resource.relation_manager.table.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'not_started' => 'gray',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        default => 'danger', // Thêm trường hợp mặc định
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'not_started' => $statusOptions['not_started'],
                        'in_progress' => $statusOptions['in_progress'],
                        'completed' => $statusOptions['completed'],
                        default => ucfirst($state), // Thêm trường hợp mặc định
                    }),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label(__('user-resource.relation_manager.table.completed_at'))
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
                            ->label(__('user-resource.relation_manager.table.status'))
                            ->options($statusOptions)
                            ->default('not_started')
                            ->required(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('user-resource.relation_manager.actions.edit_progress')),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}

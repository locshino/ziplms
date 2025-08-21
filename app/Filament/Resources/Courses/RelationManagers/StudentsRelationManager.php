<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use App\Enums\Status\UserStatus;
use App\Enums\System\RoleSystem;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->disabled()
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->disabled()
                    ->required(),
                ...$this->getTimeCoursePicker(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('avatar')
                    ->collection('avatar')
                    ->label(__('resource_user.table.columns.avatar'))
                    ->circular()
                    ->defaultImageUrl(url('/images/avatar/default.png'))
                    ->toggleable(),
                TextColumn::make('id')
                    ->label(__('resource_user.table.columns.id'))
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('resource_user.table.columns.name'))
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('resource_user.table.columns.email'))
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->label(__('resource_user.table.columns.email_verified_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label(__('resource_user.table.columns.status'))
                    ->badge()
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label(__('resource_user.table.columns.roles'))
                    ->badge()
                    ->separator(', ')
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
                    ->label(__('resource_user.table.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('resource_user.table.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('resource_user.table.columns.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->options(UserStatus::class)
                    ->label(__('resource_user.table.filters.status')),
                DateRangeFilter::make('pivot.start_at')
                    ->label('Start At'),
                DateRangeFilter::make('pivot.end_at')
                    ->label('End At'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->schema(fn(AttachAction $action): array => [
                        $action->getRecordSelect(),
                        ...$this->getTimeCoursePicker(),
                    ])
                    ->recordSelectSearchColumns(['name', 'email'])
                    ->multiple(),
            ])->recordActions([
                EditAction::make(),
                DetachAction::make(),
            ])->toolbarActions([
                BulkActionGroup::make([
                    // ...
                    DetachBulkAction::make(),
                ]),
            ]);
    }

    public function getTimeCoursePicker(): array
    {
        return [
            DateTimePicker::make('start_at')
                ->before('end_at')
                ->default(function () {
                    $owner = $this->getOwnerRecord();
                    $now = now();
                    if ($now->between($owner->start_at, $owner->end_at)) {
                        return $now;
                    }
                    return $owner->start_at;
                })
                ->minDate($this->getOwnerRecord()->start_at)
                ->maxDate($this->getOwnerRecord()->end_at)
                ->required(),
            DateTimePicker::make('end_at')
                ->after('start_at')
                ->default($this->getOwnerRecord()->end_at)
                ->minDate($this->getOwnerRecord()->start_at)
                ->maxDate($this->getOwnerRecord()->end_at)
                ->required(),
        ];
    }
}

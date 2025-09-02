<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use App\Enums\Status\AssignmentStatus;
use App\Filament\Resources\Assignments\AssignmentResource;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class AssignmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'assignments';

    protected static ?string $recordTitleAttribute = 'title';

    // protected static ?string $relatedResource = AssignmentResource::class;

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->columnSpanFull()
                    ->label('Title')
                    ->disabled()
                    ->required(),
                ...$this->getTimeCoursePicker(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('max_points')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('max_attempts')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('pivot.start_at')
                    ->label('Start At')
                    ->dateTime(),
                TextColumn::make('pivot.end_submission_at')
                    ->label('End Submission At')
                    ->dateTime(),
                TextColumn::make('pivot.start_grading_at')
                    ->label('Start Grading At')
                    ->dateTime(),
                TextColumn::make('pivot.end_at')
                    ->label('End At')
                    ->dateTime(),
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
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->options(AssignmentStatus::class),
                DateRangeFilter::make('course_assignments.start_at')
                    ->label('Start At'),
                DateRangeFilter::make('course_assignments.end_submission_at')
                    ->label('End Submission At'),
                DateRangeFilter::make('course_assignments.start_grading_at')
                    ->label('Start Grading At'),
                DateRangeFilter::make('course_assignments.end_at')
                    ->label('End At'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        ...$this->getTimeCoursePicker(),
                    ])
                    ->multiple(),
            ])
            ->toolbarActions([
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

                    return match (true) {
                        ! isset($owner->start_at) || ! isset($owner->end_at) => $now,
                        $now->between($owner->start_at, $owner->end_at) => $now,
                        default => $owner->start_at,
                    };
                })
                ->minDate($this->getOwnerRecord()->start_at)
                ->maxDate($this->getOwnerRecord()->end_at)
                ->required(),
            DateTimePicker::make('end_submission_at')
                ->after('start_at')
                ->before('start_grading_at')
                ->minDate($this->getOwnerRecord()->start_at)
                ->maxDate($this->getOwnerRecord()->end_at)
                ->required(),
            DateTimePicker::make('start_grading_at')
                ->after('end_submission_at')
                ->before('end_at')
                ->minDate($this->getOwnerRecord()->start_at)
                ->maxDate($this->getOwnerRecord()->end_at)
                ->required(),
            DateTimePicker::make('end_at')
                ->after('start_grading_at')
                ->default($this->getOwnerRecord()->end_at)
                ->minDate($this->getOwnerRecord()->start_at)
                ->maxDate($this->getOwnerRecord()->end_at)
                ->required(),
        ];
    }
}

<?php

namespace App\Filament\Imports;

use App\Enums\LocationType;
use App\Enums\SchedulableType;
use App\Models\Schedule;
use App\Models\User;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;

class ScheduleImporter extends Importer
{
    protected static ?string $model = Schedule::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->label('Title')
                ->rules(['required', 'max:255']),
            ImportColumn::make('description')
                ->label('Description')
                ->rules(['nullable']),
            ImportColumn::make('schedulable_type')
                ->label('Associated Type')
                ->rules(['required', 'in:'.implode(',', SchedulableType::values())]),
            ImportColumn::make('schedulable_id')
                ->label('Associated ID/Code')
                ->rules(['required']),
            ImportColumn::make('assigned_teacher_email')
                ->label('Assigned Teacher Email')
                ->rules(['nullable', 'email', 'exists:users,email']),
            ImportColumn::make('start_time')
                ->label('Start Time')
                ->rules(['required', 'date']),
            ImportColumn::make('end_time')
                ->label('End Time')
                ->rules(['required', 'date', 'after:start_time']),
            ImportColumn::make('location_type')
                ->label('Location Type')
                ->rules(['required', 'in:'.implode(',', LocationType::values())])
                ->fillRecordUsing(null), // Prevent trying to set 'location_type' attribute on the model
            ImportColumn::make('location_details')
                ->label('Location Details')
                ->rules(['nullable']),
            ImportColumn::make('status')
                ->label('Status')
                ->rules(['nullable', 'in:active,inactive']),
        ];
    }

    public function resolveRecord(): ?Schedule
    {
        // return Schedule::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Schedule;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your schedule import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }

    /**
     * This hook is called after the model is filled with data from the CSV,
     * but before it is saved to the database. It's the ideal place to
     * handle relationships.
     */
    protected function afterFill(): void
    {
        // 1. Resolve and associate the polymorphic 'schedulable' model.
        $schedulableTypeEnum = SchedulableType::tryFrom($this->data['schedulable_type']);
        if (! $schedulableTypeEnum) {
            // This should be caught by validation, but as a safeguard:
            throw new RowImportFailedException('Invalid schedulable type provided: '.$this->data['schedulable_type']);
        }

        $modelClass = $schedulableTypeEnum->getModelClass();
        // Assuming 'schedulable_id' from CSV is the actual ID.
        // If it's a unique code, you'd use: $modelClass::where('code', $this->data['schedulable_id'])->first();
        $schedulable = $modelClass::find($this->data['schedulable_id']);

        if (! $schedulable) {
            throw new RowImportFailedException('Associated record not found for type "'.$this->data['schedulable_type'].'" with ID "'.$this->data['schedulable_id'].'".');
        }

        $this->getRecord()->schedulable()->associate($schedulable);

        // 2. Resolve and associate the 'assignedTeacher'.
        if (! empty($this->data['assigned_teacher_email'])) {
            $teacher = User::where('email', $this->data['assigned_teacher_email'])->first();

            if (! $teacher) {
                // Manually fail the row if email is provided but not found
                throw new RowImportFailedException('Teacher with email '.$this->data['assigned_teacher_email'].' not found.');
            }

            $this->getRecord()->assignedTeacher()->associate($teacher);
        }

        // 3. Associate the currently authenticated user as the creator.
        $this->getRecord()->creator()->associate(Filament::auth()->user());
    }

    /**
     * This hook is called after the model has been saved to the database.
     * It's the perfect place for actions that require the model to have an ID, like attaching tags.
     */
    protected function afterSave(): void
    {
        // The 'location_type' from the CSV is used as a tag.
        // We use syncTagsWithType to ensure only one tag of this type is attached.
        if (! empty($this->data['location_type'])) {
            $this->getRecord()->syncTagsWithType([$this->data['location_type']], LocationType::key());
        }
    }
}

<?php

namespace App\Filament\Imports;

use App\Enums\Status\CourseStatus;
use App\Libs\Roles\RoleHelper;
use App\Models\Course;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Select;
use Illuminate\Support\Number;

class CourseImporter extends Importer
{
    protected static ?string $model = Course::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->example('Introduction to Programming')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('description')
                ->example('Learn the basics of programming using Python.'),
            ImportColumn::make('teacher')
                ->example('teacher@example.com')
                ->requiredMapping()
                ->relationship(resolveUsing: function (string $state): ?User {
                    return User::query()
                        ->where('email', $state)
                        ->first();
                })
                ->rules([
                    'required',
                    'email',
                    function ($attribute, $value, $fail) {
                        $user = User::where('email', $value)->first();

                        $msg = match (true) {
                            ! $user => 'The selected teacher does not exist.',
                            ! RoleHelper::isTeacher($user) => 'The selected teacher must have the role of teacher.',
                            default => null,
                        };

                        if ($msg) {
                            $fail($msg);
                        }
                    },
                ]),
            ImportColumn::make('start_at')
                ->example(now()->toDateTimeString())
                ->rules(['date_format:Y-m-d H:i:s']),
            ImportColumn::make('end_at')
                ->example(now()->addMonth()->toDateTimeString())
                ->rules(['date_format:Y-m-d H:i:s']),
            ImportColumn::make('price')
                ->example('100000')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('is_featured')
                ->example('true')
                ->boolean()
                ->rules(['boolean']),
        ];
    }

    public function resolveRecord(): Course
    {
        $course = new Course;

        $course->status = $this->options['default_status'] ?? CourseStatus::DRAFT->value;

        return $course;
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Select::make('default_status')
                ->options(CourseStatus::class)
                ->required()
                ->helperText('The status to assign to the imported courses.'),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your course import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }

    public function getValidationMessages(): array
    {
        return [
            'title.required' => 'The course title is required.',
            'teacher.required' => 'The teacher field is required.',
            'teacher.email' => 'The teacher must be a valid email address.',
            'teacher.exists' => 'The teacher must exist in the users table.',
            'teacher.custom' => 'The selected teacher must have the role of teacher.',
            'start_at.datetime' => 'The start date must be a valid datetime.',
            'end_at.datetime' => 'The end date must be a valid datetime.',
            'price.integer' => 'The price must be an integer.',
            'is_featured.boolean' => 'The featured field must be true or false.',
        ];
    }
}

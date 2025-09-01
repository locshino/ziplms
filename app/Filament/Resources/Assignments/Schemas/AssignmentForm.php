<?php

namespace App\Filament\Resources\Assignments\Schemas;

use App\Enums\MimeType;
use App\Enums\Status\AssignmentStatus;
use App\Models\Assignment;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Malzariey\FilamentLexicalEditor\LexicalEditor;

class AssignmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin cơ bản')
                    ->columnSpanFull()
                    ->components([
                        TextInput::make('title')
                            ->label(__('resource_assignment.form.fields.title'))
                            ->required(),
                        LexicalEditor::make('description')
                            ->label(__('resource_assignment.form.fields.description'))
                            ->columnSpanFull(),
                        Section::make('Thiết lập')
                            ->columns(4)
                            ->components([
                                TextInput::make('max_points')
                                    ->label(__('resource_assignment.form.fields.max_points'))
                                    ->required()
                                    ->numeric()
                                    ->default(10),
                                TextInput::make('max_attempts')
                                    ->label(__('resource_assignment.form.fields.max_attempts'))
                                    ->minValue(0)
                                    ->numeric(),
                                Select::make('status')
                                    ->label(__('resource_assignment.form.fields.status'))
                                    ->options(AssignmentStatus::class)
                                    ->required(),
                            ]),

                        SpatieTagsInput::make('tags')
                            ->label(__('resource_assignment.form.fields.tags'))
                            ->type(Assignment::class),
                    ]),

                Section::make('Tài liệu')
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('course_documents')
                            ->label(__('resource_assignment.form.fields.course_documents'))
                            ->collection('assignment_documents')
                            ->multiple()
                            ->preserveFilenames()
                            ->acceptedFileTypes([
                                ...MimeType::documents(),
                                ...MimeType::images(),
                                ...MimeType::archives(),
                            ])
                            ->maxSize(10240) // 10MB
                            ->helperText('Tải lên các tài liệu liên quan đến bài tập (PDF, Word, Excel, hình ảnh, v.v.)')
                            ->reorderable()
                            ->downloadable()
                            ->openable()
                            ->customProperties(fn ($file) => ['title' => $file ? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) : 'document']),
                    ]),
            ]);
    }
}

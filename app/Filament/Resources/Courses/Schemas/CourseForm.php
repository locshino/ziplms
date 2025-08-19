<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Enums\MimeType;
use App\Enums\Status\CourseStatus;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\Course;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\ModalTableSelect;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Malzariey\FilamentLexicalEditor\LexicalEditor;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin cơ bản')
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('course_cover')
                            ->label('Hình ảnh khóa học')
                            ->collection('course_cover')
                            ->image()
                            ->multiple(false)
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                            ])
                            ->maxSize(2048)
                            ->acceptedFileTypes(MimeType::images())
                            ->columnSpanFull(),
                        TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $baseSlug = Str::slug($state);
                                $randomNumber = mt_rand(1000, 9999);
                                $slug = "{$baseSlug}-{$randomNumber}";

                                $set('slug', $slug);
                            }),
                        ModalTableSelect::make('teacher_id')
                            ->relationship('teacher', 'name')
                            ->tableConfiguration(UsersTable::class)
                            ->required(),
                        DateTimePicker::make('start_at'),
                        DateTimePicker::make('end_at'),
                        Select::make('status')
                            ->options(CourseStatus::class)
                            ->required(),
                        SpatieTagsInput::make('tags')
                            ->label('Phân loại')
                            ->type(Course::class),
                    ]),
                Section::make('Tài liệu')
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('course_documents')
                            ->label('Tài liệu khóa học')
                            ->collection('course_documents')
                            ->multiple()
                            ->acceptedFileTypes([
                                ...MimeType::documents(),
                                ...MimeType::images(),
                                ...MimeType::archives(),
                            ])
                            ->maxSize(10240) // 10MB
                            ->helperText('Tải lên các tài liệu liên quan đến khóa học (PDF, Word, Excel, hình ảnh, v.v.)')
                            ->reorderable()
                            ->downloadable()
                            ->openable()
                            ->mediaName(fn ($file) => $file ? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) : 'document')
                            ->customProperties(fn ($file) => ['title' => $file ? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) : 'document']),
                    ]),
                Section::make('Mở rộng')
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextInput::make('slug')
                            ->unique(ignoreRecord: true)
                            ->rules(['regex:/^[a-z0-9-]+$/'])
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('slug', Str::slug($state));
                            }),
                        TextInput::make('price')
                            ->numeric()
                            ->prefix('VND'),
                        Toggle::make('is_featured')
                            ->required(),
                        LexicalEditor::make('description')
                            ->columnSpanFull(),
                    ]),

            ]);
    }
}

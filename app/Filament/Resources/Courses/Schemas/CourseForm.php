<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Enums\MimeType;
use App\Enums\Status\CourseStatus;
use App\Filament\Resources\Users\Tables\TeachersTable;
use App\Models\Course;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\ModalTableSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
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

                        Section::make('Thiết lập')
                            ->columnSpanFull()
                            ->columns(3)
                            ->schema([
                                ModalTableSelect::make('teacher_id')
                                    ->relationship('teacher', 'name')
                                    ->tableConfiguration(TeachersTable::class)
                                    ->required(),
                                Select::make('status')
                                    ->options(CourseStatus::class)
                                    ->required(),
                                SpatieTagsInput::make('tags')
                                    ->label('Phân loại')
                                    ->type(Course::class),
                            ]),

                        Section::make('Thời gian')
                            ->columnSpanFull()
                            ->columns(2)
                            ->schema([
                                DateTimePicker::make('start_at')
                                    ->label('Thời gian bắt đầu')
                                    ->disabled(function (?Course $record) {
                                        if (! $record) {
                                            return false; // Always enabled on create
                                        }
                                        // Rule 1: Disable if it's an evergreen course with students
                                        if ($record->start_at === null && $record->students()->exists()) {
                                            return true;
                                        }
                                        // Rule 2: Disable if the timed course is currently running
                                        if ($record->start_at && now()->between($record->start_at, $record->end_at)) {
                                            return true;
                                        }

                                        return false;
                                    })
                                    ->helperText(function (?Course $record) {
                                        if ($record && $record->start_at === null && $record->students()->exists()) {
                                            return 'Không thể đặt lại lịch vì khóa học đã có học viên.';
                                        }

                                        return null;
                                    }),

                                DateTimePicker::make('end_at')
                                    ->label('Thời gian kết thúc')
                                    ->disabled(function (?Course $record) {
                                        if (! $record) {
                                            return false;
                                        }
                                        // Rule 1: Disable if it's an evergreen course with students
                                        if ($record->start_at === null && $record->students()->exists()) {
                                            return true;
                                        }

                                        return false;
                                    }),
                            ]),

                        Action::make('setEvergreen')
                            ->label('Chuyển sang Vô thời hạn')
                            ->color('info')
                            ->icon('heroicon-o-calendar-days')
                            ->requiresConfirmation()
                            ->visible(function (?Course $record) {
                                if (! $record) {
                                    return false;
                                }

                                // Only show if the course is currently timed
                                return $record->start_at !== null;
                            })
                            ->modalHeading('Xác nhận chuyển đổi sang Vô thời hạn')
                            ->modalDescription(new HtmlString(
                                'Hành động này sẽ xóa lịch trình của khóa học. <br/><br/>'.
                                    '<strong class="text-danger">Cảnh báo: Nếu khóa học đã có học viên, bạn sẽ không thể thiết lập lại lịch cho khóa học này nữa. Thay đổi sẽ có hiệu lực khi bấm lưu thay đổi</strong>'
                            ))
                            ->modalSubmitActionLabel('Có, tôi hiểu và xác nhận')
                            ->action(function (Set $set) {
                                $set('start_at', null);
                                $set('end_at', null);
                            }),
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
                            ->prefix(config('ziplms.currency.default')),
                        Toggle::make('is_featured')
                            ->required(),
                        LexicalEditor::make('description')
                            ->columnSpanFull(),
                    ]),

            ]);
    }
}

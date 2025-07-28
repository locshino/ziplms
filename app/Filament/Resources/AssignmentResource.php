<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssignmentResource\Pages;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Repositories\Contracts\AssignmentRepositoryInterface;
use App\Repositories\Contracts\SubmissionRepository;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Tags\Tag;
use Dvarilek\FilamentTableSelect\Components\Form\TableSelect;

class AssignmentResource extends Resource
{
    use Translatable;

    protected static ?string $model = Assignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TableSelect::make('course_id')
                    ->label(__('assignment.labels.course'))
                    ->relationship('course', 'name')
                    ->tableLocation(CourseResource::class),
                Forms\Components\TextInput::make('title')
                    ->label(__('assignment.labels.title'))
                    ->required(),

                Group::make([
                    Radio::make('instructions_type')
                        ->label(__('assignment.labels.instructions_type'))
                        ->options([
                            'text' => 'Nhập văn bản',
                            'file' => 'Tải file',
                            'url' => 'Liên kết',
                        ])
                        ->reactive()
                        ->required(),

                    Textarea::make('instructions_text')
                        ->label(__('assignment.labels.instructions_text'))
                        ->visible(fn($get) => $get('instructions_type') === 'text'),

                    FileUpload::make('instructions_file')
                        ->label(__('assignment.labels.instructions_file'))
                        ->disk('public')
                        ->directory('assignments')
                        ->visible(fn($get) => $get('instructions_type') === 'file')
                        ->preserveFilenames()
                        ->acceptedFileTypes([
                            'application/pdf',
                            'application/msword',
                            'application/zip',
                            'application/x-zip-compressed',
                        ]),
                    TextInput::make('instructions_url')
                        ->label(__('assignment.labels.instructions_url'))
                        ->url()
                        ->placeholder('https://...')
                        ->visible(fn($get) => $get('instructions_type') === 'url'),

                ]),

                Forms\Components\TextInput::make('max_score')
                    ->label(__('assignment.labels.max_score'))
                    ->numeric()
                    ->default(0)->minValue(0)->maxValue(100),

                Forms\Components\DateTimePicker::make('due_date')
                    ->label(__('assignment.labels.due_date')),

                Forms\Components\Toggle::make('allow_late_submissions')
                    ->label(__('assignment.labels.allow_late_submissions')),

                Forms\Components\Select::make('status')
                    ->label(__('assignment.labels.status'))
                    ->options([
                        'draft' => 'Nháp',
                        'published' => 'Công khai',
                        'closed' => 'Đã đóng',
                        'cancelled' => 'Hủy bỏ',
                    ])
                    ->required(),

                Forms\Components\TagsInput::make('tags')
                    ->label(__('assignment.labels.tags'))
                    ->suggestions(Tag::pluck('name')->toArray())
                    ->saveRelationshipsUsing(function ($record, $state) {
                        $record->syncTags($state);
                    }),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('title')->label(__('assignment.label.title'))->limit(50)->searchable(),
                TextColumn::make('course.name')->label(__('assignment.label.course')),
                TextColumn::make('max_score')->label(__('assignment.label.max_score')),
                TextColumn::make('due_date')->label(__('assignment.label.due_date'))->dateTime(),
                BooleanColumn::make('allow_late_submissions')->label(__('assignment.label.allow_late_submissions')),
                TextColumn::make('creator.name')->label(__('assignment.label.creator'))->searchable()
                    ->visible(fn() => app(AssignmentRepositoryInterface::class)->isStudent()),
                TagsColumn::make('tags')
                    ->label(__('assignment.label.tags'))
                    ->getStateUsing(function ($record) {

                        return $record->tags ? $record->tags->pluck('name')->toArray() : [];
                    })
                    ->visible(fn() => app(AssignmentRepositoryInterface::class)->isStudent()),

                BadgeColumn::make('status')
                    ->label(__('assignment.label.status'))
                    ->color(fn($state) => $state::color())
                    ->formatStateUsing(fn($state) => $state::label())
                    ->visible(fn() => app(AssignmentRepositoryInterface::class)->isStudent()),

            ])

            ->filters([
                Tables\Filters\SelectFilter::make('course_id')
                    ->label(__('assignment.filters.filter_by_course'))
                    ->relationship('course', 'name'),
                SelectFilter::make('course.organization.classesMajors')
                    ->label('Lọc theo Lớp học')
                    ->relationship('course.organization.classesMajors', 'name')
                    ->placeholder('Chọn lớp học'),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('submit')
                    ->label('Nộp bài')
                    ->button()
                    ->color('success')
                    ->icon('heroicon-o-arrow-up-circle')
                    ->visible(fn($record) => ($record->due_date > now() || $record->status === 'published') && auth()->user()->hasRole('student'))
                    ->form([
                        FileUpload::make('submission_file')
                            ->label(__('assignment.labels.instructions_file'))
                            ->disk('public')
                            ->directory('submissions')

                            ->preserveFilenames()
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/zip',
                                'application/x-zip-compressed',
                            ])
                            ->getUploadedFileNameForStorageUsing(function ($file, callable $get, callable $set) {
                                $user = auth()->user();
                                $username = Str::slug($user->name);
                                $filename = $username . '-' . now()->timestamp . '.' . $file->getClientOriginalExtension();

                                return $file->storeAs('submissions', $filename, 'public');
                            }),

                    ])
                    ->visible(fn() => !Auth::user()->hasRole('teacher'))
                    ->action(function (array $data, Assignment $record) {

                        $submissionRepo = app(SubmissionRepository::class);

                        $submissionRepo->submitAssignment($record, $data, auth()->id());

                        Notification::make()
                            ->title('Nộp bài thành công')
                            ->success()
                            ->send();
                    }),
                ActionGroup::make([
                    Tables\Actions\Action::make('view_submissions')
                        ->label('Xem bài nộp')
                        ->icon('heroicon-o-document')
                        ->modalHeading('Danh sách file đã nộp')
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Đóng')
                        ->modalContent(function ($record) {
                            $userId = auth()->id();

                            $submission = AssignmentSubmission::query()
                                ->where('assignment_id', $record->id)
                                ->where('user_id', $userId)
                                ->get();
                            if ($submission->isEmpty()) {
                                return new \Illuminate\Support\HtmlString('<p class="text-gray-600">Chưa có bài nộp.</p>');
                            }

                            $listItems = $submission->map(function ($submission) {
                                $fileUrl = asset('storage/' . ltrim($submission->submission_text, '/'));
                                $fileName = basename($submission->submission_text);

                                return '<li><a href="' . e($fileUrl) . '" target="_blank" class="text-blue-600 hover:underline">' . e($fileName) . '</a></li>';
                            })->implode('');

                            return new \Illuminate\Support\HtmlString('<ul class="list-disc pl-4">' . $listItems . '</ul>');

                        }),

                ])->visible(fn() => Auth::user()->hasRole('student')),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema(components: [
            TextEntry::make('title')
                ->label(__('assignment.labels.title'))
                ->columnSpanFull()
                ->extraAttributes([
                    'class' => 'text-lg font-semibold !text-gray-600',
                ])
                ->icon('heroicon-o-document-text'),
            TextEntry::make('creator.name')
                ->label('Người giao bài')->icon('heroicon-o-user'),

            TextEntry::make('created_at')
                ->label('Đã đăng vào')
                ->dateTime('d/m/Y H:i')
                ->icon('heroicon-o-calendar'),

            TextEntry::make('instructions_text')
                ->label('Đề bài')
                ->default(fn($record) => app(AssignmentRepositoryInterface::class)->getInstructionsText($record))
                ->visible(fn($record) => !empty(optional($record->getTranslation('instructions', 'vi'))['text'] ?? null)),

            TextEntry::make('instructions_url')
                ->label('Link đề bài')
                ->url(fn($record) => is_array($vi = $record->getTranslation('instructions', 'vi')) ? ($vi['url'] ?? null) : null)
                ->default(fn($record) => is_array($vi = $record->getTranslation('instructions', 'vi')) ? ($vi['url'] ?? null) : null)
                ->openUrlInNewTab()
                ->visible(fn($record) => is_array($vi = $record->getTranslation('instructions', 'vi')) && !empty($vi['url'] ?? null)),

            TextEntry::make('instructions_file')
                ->label('Tệp đính kèm')
                ->url(fn($record) => app(AssignmentRepositoryInterface::class)->getInstructionsFileUrl($record))
                ->default(fn($record) => app(AssignmentRepositoryInterface::class)->getInstructionsFileDefault($record))
                ->openUrlInNewTab()
                ->visible(fn($record) => app(AssignmentRepositoryInterface::class)->shouldShowInstructionsFile($record) !== null),

        ]);

    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()?->hasRole('teacher')) {
            $query->where('created_by', auth()->id());
        } elseif (auth()->user()?->hasRole('student')) {
            $query->where('status', 'published');
        }

        return $query;
    }

    public static function getModelLabel(): string
    {
        return __('assignment.title');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssignments::route('/'),
            'create' => Pages\CreateAssignment::route('/create'),
            'edit' => Pages\EditAssignment::route('/{record}/edit'),
        ];
    }
}

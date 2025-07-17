<?php

namespace App\Filament\Teacher\Resources;

use App\Filament\Teacher\Resources\AssignmentResource\Pages;
use App\Models\Assignment;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Tags\Tag;

class AssignmentResource extends Resource
{
    use Translatable;

    protected static ?string $model = Assignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->label('Khóa học')
                    ->relationship('course', 'name')
                    ->required(),

                Forms\Components\TextInput::make('title')
                    ->label('Tiêu đề')
                    ->required(),

                Group::make([
                    Radio::make('instructions_type')
                        ->label('Loại đề bài')
                        ->options([
                            'text' => 'Nhập văn bản',
                            'file' => 'Tải file',
                            'url' => 'Liên kết',
                        ])
                        ->reactive()
                        ->required(),

                    Textarea::make('instructions_text')
                        ->label('Nhập đề bài')
                        ->visible(fn ($get) => $get('instructions_type') === 'text'),

                    FileUpload::make('instructions_file')
                        ->label('Tệp đề bài')
                        ->disk('public')
                        ->directory('assignments')
                        ->visible(fn ($get) => $get('instructions_type') === 'file')
                        ->preserveFilenames()
                        ->acceptedFileTypes(['application/pdf', 'application/msword']),
                    TextInput::make('instructions_url')
                        ->label('Link đề bài')
                        ->url()
                        ->placeholder('https://...')
                        ->visible(fn ($get) => $get('instructions_type') === 'url'),

                ]),

                Forms\Components\TextInput::make('max_score')
                    ->numeric()
                    ->default(0)->minValue(0)->maxValue(100),

                Forms\Components\DateTimePicker::make('due_date')
                    ->label('Hạn nộp'),

                Forms\Components\Toggle::make('allow_late_submissions')
                    ->label('Cho phép nộp trễ'),

                Forms\Components\Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Tích cực',
                        'inactive' => 'Không hoạt động',
                    ])
                    ->required(),

                Forms\Components\TagsInput::make('tags')
                    ->label('Thẻ')
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
                TextColumn::make('title')->label('Tiêu đề')->limit(50)->searchable(),
                TextColumn::make('course.name')->label('Khóa học'),
                TextColumn::make('max_score')->label('Điểm'),
                TextColumn::make('due_date')->label('Hạn nộp')->dateTime(),
                BooleanColumn::make('allow_late_submissions')->label('Cho phép trễ'),
                TextColumn::make('creator.name')->label('Người tạo')->searchable(),
                TagsColumn::make('tags_string')->label('Thẻ'),
                BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->color(fn ($state) => $state::color())
                    ->formatStateUsing(fn ($state) => $state::label()),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('course_id')
                    ->label('Lọc theo Khóa học')
                    ->relationship('course', 'name'),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
        return $infolist->schema([
            TextEntry::make('title')
                ->label('Tiêu đề')
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
                ->default(fn ($record) => optional($record->getTranslation('instructions', 'vi'))['text'] ?? null)
                ->visible(fn ($record) => ! empty(optional($record->getTranslation('instructions', 'vi'))['text'] ?? null)),

            TextEntry::make('instructions_url')
                ->label('Link đề bài')
                ->url(fn ($record) => is_array($vi = $record->getTranslation('instructions', 'vi')) ? ($vi['url'] ?? null) : null)
                ->default(fn ($record) => is_array($vi = $record->getTranslation('instructions', 'vi')) ? ($vi['url'] ?? null) : null)
                ->openUrlInNewTab()
                ->visible(fn ($record) => is_array($vi = $record->getTranslation('instructions', 'vi')) && ! empty($vi['url'] ?? null)),

            TextEntry::make('instructions_file')
                ->label('Tệp đính kèm')
                ->url(function ($record) {
                    $vi = $record->getTranslation('instructions', 'vi');
                    $filePath = is_array($vi) ? ($vi['file'] ?? $vi['en'] ?? null) : null;

                    return $filePath ? asset('storage/'.$filePath) : null;
                })
                ->default(function ($record) {
                    $vi = $record->getTranslation('instructions', 'vi');

                    return is_array($vi) ? ($vi['file'] ?? $vi['en'] ?? null) : null;
                })
                ->openUrlInNewTab()
                ->visible(function ($record) {
                    $vi = $record->getTranslation('instructions', 'vi');

                    return is_array($vi) && (! empty($vi['file'] ?? null) || ! empty($vi['en'] ?? null));
                }),
        ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()?->hasRole('teacher')) {
            $query->where('created_by', auth()->id());
        }

        return $query;
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

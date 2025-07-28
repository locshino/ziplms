<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExamAttemptResource\Pages;
use App\Filament\Resources\ExamAttemptResource\RelationManagers;
use App\Models\Course;
use App\Models\ExamAttempt;
use App\States\Exam\Completed;
use App\States\Exam\Status;
use Filament\Forms;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExamAttemptResource extends Resource
{
    use Translatable;

    protected static ?string $model = ExamAttempt::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    public static function getNavigationGroup(): ?string
    {
        return __('exam-attempt-resource.navigation.group');
    }

    public static function getModelLabel(): string
    {
        return __('exam-attempt-resource.navigation.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('exam-attempt-resource.navigation.plural_label');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // SỬA LẠI SECTION NÀY: Thêm cột Course và đổi layout thành 3 cột
                Components\Section::make(__('exam-attempt-resource.infolist.section.general_info'))
                    ->columns(3) // <-- Đổi thành 3 cột
                    ->schema([
                        Components\TextEntry::make('exam.title')->label(__('exam-attempt-resource.infolist.field.exam_title')),
                        // THÊM TRƯỜNG NÀY: Hiển thị tên khóa học
                        Components\TextEntry::make('exam.course.name')->label('Khóa học'),
                        Components\TextEntry::make('user.name')->label(__('exam-attempt-resource.infolist.field.student_name')),
                    ]),
                Components\Section::make(__('exam-attempt-resource.infolist.section.results'))->columns(3)->schema([
                    Components\TextEntry::make('score')->label(__('exam-attempt-resource.infolist.field.score'))->badge()->color('success')->numeric()->visible(fn($record): bool => $record->status instanceof Completed),
                    Components\TextEntry::make('status')->label(__('exam-attempt-resource.infolist.field.status'))->badge()->color(fn(Status $state): string => $state->color()),
                    Components\TextEntry::make('time_spent_seconds')->label(__('exam-attempt-resource.infolist.field.time_spent'))->formatStateUsing(fn(?int $state): string => $state ? gmdate('H:i:s', $state) : 'N/A'),
                ]),
                Components\Section::make(__('exam-attempt-resource.infolist.section.timestamps'))->columns(2)->schema([
                    Components\TextEntry::make('started_at')->label(__('exam-attempt-resource.infolist.field.started_at'))->dateTime('d/m/Y H:i:s'),
                    Components\TextEntry::make('completed_at')->label(__('exam-attempt-resource.infolist.field.completed_at'))->dateTime('d/m/Y H:i:s'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('exam.title')
                    ->label(__('exam-attempt-resource.table.column.exam_title'))
                    ->searchable()->sortable(),

                // THÊM CỘT NÀY: Hiển thị tên khóa học trong bảng
                Tables\Columns\TextColumn::make('exam.course.name')
                    ->label('Khóa học')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('exam-attempt-resource.table.column.student_name'))
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('score')
                    ->label(__('exam-attempt-resource.table.column.score'))
                    ->sortable()->numeric()
                    ->placeholder(__('answers-relation-manager.table.placeholders.not_graded')),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('exam-attempt-resource.table.column.status'))
                    ->badge()
                    ->color(fn(Status $state): string => $state->color()),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label(__('exam-attempt-resource.table.column.submission_date'))
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                // THÊM BỘ LỌC NÀY: Lọc các bài làm theo khóa học
                Tables\Filters\Filter::make('course')
                    ->form([
                        Forms\Components\Select::make('course_id')
                            ->label('Khóa học')
                            ->options(Course::query()->pluck('name', 'id')->toArray())
                            ->searchable()
                            ->preload(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['course_id'])) {
                            return $query;
                        }
                        // Dùng whereHas để lọc qua quan hệ
                        return $query->whereHas('exam', fn(Builder $q) => $q->where('course_id', $data['course_id']));
                    }),

                Tables\Filters\SelectFilter::make('exam_id')
                    ->label(__('exam-attempt-resource.table.column.exam_title'))
                    ->relationship('exam', 'title')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('exam-attempt-resource.table.column.status'))
                    ->options(Status::getOptions())
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label(__('exam-attempt-resource.table.action.view_details')),
                Tables\Actions\EditAction::make()->label(__('exam-attempt-resource.table.action.grade')),
                Tables\Actions\DeleteAction::make()->label(__('exam-attempt-resource.table.action.delete')),
            ])
            ->bulkActions([]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AnswersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExamAttempts::route('/'),
            'view' => Pages\ViewExamAttempt::route('/{record}'),
            'edit' => Pages\EditExamAttempt::route('/{record}/edit'),
            'review' => Pages\ReviewAnswer::route('/answer/{record}/review'),
        ];
    }
}

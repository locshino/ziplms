<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExamAttemptResource\Pages;
use App\Filament\Resources\ExamAttemptResource\RelationManagers;
use App\Models\ExamAttempt;
use App\States\Exam\Status;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Concerns\Translatable;

class ExamAttemptResource extends Resource
{
    use Translatable;
    protected static ?string $model = ExamAttempt::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    // [CẬP NHẬT] Sử dụng các phương thức get* để gọi file ngôn ngữ
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
        return parent::getEloquentQuery()->withSum('answers', 'points_earned');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make(__('exam-attempt-resource.infolist.section.general_info'))
                    ->columns(2)
                    ->schema([
                        Components\TextEntry::make('exam.title')
                            ->label(__('exam-attempt-resource.infolist.field.exam_title')),
                        Components\TextEntry::make('user.name')
                            ->label(__('exam-attempt-resource.infolist.field.student_name')),
                    ]),
                Components\Section::make(__('exam-attempt-resource.infolist.section.results'))
                    ->columns(3)
                    ->schema([
                        Components\TextEntry::make('answers_sum_points_earned')
                            ->label(__('exam-attempt-resource.infolist.field.score'))
                            ->badge()
                            ->color('success')
                            ->numeric(),

                        Components\TextEntry::make('status')
                            ->label(__('exam-attempt-resource.infolist.field.status'))
                            ->badge()
                            ->color(fn(Status $state): string => $state->color()),

                        Components\TextEntry::make('time_spent_seconds')
                            ->label(__('exam-attempt-resource.infolist.field.time_spent'))
                            ->formatStateUsing(fn(?int $state): string => $state ? gmdate('H:i:s', $state) : 'N/A'),
                    ]),
                Components\Section::make(__('exam-attempt-resource.infolist.section.timestamps'))
                    ->columns(2)
                    ->schema([
                        Components\TextEntry::make('started_at')
                            ->label(__('exam-attempt-resource.infolist.field.started_at'))
                            ->dateTime('d/m/Y H:i:s'),
                        Components\TextEntry::make('completed_at')
                            ->label(__('exam-attempt-resource.infolist.field.completed_at'))
                            ->dateTime('d/m/Y H:i:s'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('exam.title')->label(__('exam-attempt-resource.table.column.exam_title'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label(__('exam-attempt-resource.table.column.student_name'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('answers_sum_points_earned')
                    ->label(__('exam-attempt-resource.table.column.score'))
                    ->sortable()
                    ->numeric(),
                Tables\Columns\TextColumn::make('status')->label(__('exam-attempt-resource.table.column.status'))->badge()
                    ->color(fn(Status $state): string => $state->color()),
                Tables\Columns\TextColumn::make('completed_at')->label(__('exam-attempt-resource.table.column.submission_date'))->dateTime('d/m/Y')->sortable(),
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
        ];
    }
}

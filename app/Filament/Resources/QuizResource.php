<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResource\Pages;
use App\Models\Quiz;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Quiz';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->label('Khóa học')
                    ->options(Course::all()->pluck('title', 'id'))
                    ->required()
                    ->searchable(),

                Forms\Components\TextInput::make('title')
                    ->label('Tiêu đề')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Mô tả')
                    ->rows(3),

                Forms\Components\TextInput::make('max_points')
                    ->label('Điểm tối đa')
                    ->numeric()
                    ->default(100.00)
                    ->step(0.01)
                    ->required(),

                Forms\Components\TextInput::make('max_attempts')
                    ->label('Số lần làm tối đa')
                    ->numeric()
                    ->minValue(1),

                Forms\Components\Toggle::make('is_single_session')
                    ->label('Phiên làm bài duy nhất')
                    ->default(false),

                Forms\Components\TextInput::make('time_limit_minutes')
                    ->label('Thời gian làm bài (phút)')
                    ->numeric()
                    ->minValue(1),

                Forms\Components\DateTimePicker::make('start_at')
                    ->label('Thời gian bắt đầu')
                    ->required(),

                Forms\Components\DateTimePicker::make('end_at')
                    ->label('Thời gian kết thúc')
                    ->required()
                    ->after('start_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Khóa học')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('max_points')
                    ->label('Điểm tối đa')
                    ->sortable(),

                Tables\Columns\TextColumn::make('max_attempts')
                    ->label('Số lần làm')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_single_session')
                    ->label('Phiên duy nhất')
                    ->boolean(),

                Tables\Columns\TextColumn::make('time_limit_minutes')
                    ->label('Thời gian (phút)')
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_at')
                    ->label('Bắt đầu')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_at')
                    ->label('Kết thúc')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tạo lúc')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),

                Tables\Filters\SelectFilter::make('course_id')
                    ->label('Khóa học')
                    ->options(Course::all()->pluck('title', 'id'))
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->hasRole(['super_admin', 'admin', 'manager', 'teacher'])),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->hasRole(['super_admin', 'admin', 'manager', 'teacher']))
                    ->successNotification(
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Quiz đã được xóa')
                            ->body('Quiz đã được xóa thành công.')
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasRole(['super_admin', 'admin', 'manager', 'teacher']))
                        ->successNotification(
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Quiz đã được xóa')
                                ->body('Các quiz đã được xóa thành công.')
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasRole(['super_admin', 'admin', 'manager', 'teacher']))
                        ->successNotification(
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Quiz đã được xóa vĩnh viễn')
                                ->body('Các quiz đã được xóa vĩnh viễn.')
                        ),
                    Tables\Actions\RestoreBulkAction::make()
                        ->visible(fn () => auth()->user()->hasRole(['super_admin', 'admin', 'manager', 'teacher']))
                        ->successNotification(
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Quiz đã được khôi phục')
                                ->body('Các quiz đã được khôi phục thành công.')
                        ),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
            'manage-questions' => Pages\ManageQuestions::route('/{record}/questions'),
            'view-attempts' => Pages\ViewAttempts::route('/{record}/attempts'),
            'take-quiz' => Pages\TakeQuiz::route('/{record}/take'),
            'quiz-result' => Pages\QuizResult::route('/{record}/result/{attempt}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        // Super admin có thể truy cập tất cả
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Kiểm tra quyền cơ bản
        return $user->can('view_quizzes');
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        // Super admin có thể tạo quiz
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Kiểm tra quyền tạo quiz
        return $user->can('create_quizzes') && $user->hasRole(['admin', 'manager', 'teacher']);
    }
}

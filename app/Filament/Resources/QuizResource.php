<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResource\Pages;
use App\Filament\Resources\QuizResource\RelationManagers;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use HayderHatem\FilamentExcelImport\Actions\Concerns\CanImportExcelRecords;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class QuizResource extends Resource
{
    use CanImportExcelRecords;

    protected static ?string $model = Quiz::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Quản lý';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->label(__('quiz_resource.fields.course_id'))
                    ->relationship('course', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('title')
                    ->label(__('quiz_resource.fields.title'))
                    ->required()
                    ->maxLength(255)
                    ->minLength(3)
                    ->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('description')
                    ->label(__('quiz_resource.fields.description'))
                    ->columnSpanFull()
                    ->maxLength(1000)
                    ->rows(3),
                Forms\Components\TextInput::make('max_points')
                    ->label(__('quiz_resource.fields.max_points'))
                    ->required()
                    ->numeric()
                    ->default(100)
                    ->minValue(1)
                    ->maxValue(1000)
                    ->step(0.01),
                Forms\Components\TextInput::make('max_attempts')
                    ->label(__('quiz_resource.fields.max_attempts'))
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(10)
                    ->helperText('Để trống nếu không giới hạn số lần làm bài'),
                Forms\Components\Toggle::make('is_single_session')
                    ->label(__('quiz_resource.fields.is_single_session'))
                    ->required()
                    ->default(false)
                    ->helperText('Bật nếu học viên phải hoàn thành quiz trong một phiên duy nhất'),
                Forms\Components\TextInput::make('time_limit_minutes')
                    ->label(__('quiz_resource.fields.time_limit_minutes'))
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(480)
                    ->helperText('Thời gian tối đa để hoàn thành quiz (phút). Để trống nếu không giới hạn thời gian'),
                Forms\Components\DateTimePicker::make('start_at')
                    ->label(__('quiz_resource.fields.start_at'))
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y H:i')
                    ->afterOrEqual('today')
                    ->helperText('Thời gian bắt đầu mở quiz cho học viên làm bài'),
                Forms\Components\DateTimePicker::make('end_at')
                    ->label(__('quiz_resource.fields.end_at'))
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y H:i')
                    ->after('start_at')
                    ->helperText('Thời gian đóng quiz, sau thời điểm này học viên không thể làm bài'),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('quiz_resource.columns.id'))
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('course.title')
                    ->label(__('quiz_resource.columns.course_title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('quiz_resource.columns.title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('max_points')
                    ->label(__('quiz_resource.columns.max_points'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_attempts')
                    ->label(__('quiz_resource.columns.max_attempts'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_single_session')
                    ->label(__('quiz_resource.columns.is_single_session'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('time_limit_minutes')
                    ->label(__('quiz_resource.columns.time_limit_minutes'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_at')
                    ->label(__('quiz_resource.columns.start_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_at')
                    ->label(__('quiz_resource.columns.end_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('quiz_resource.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('quiz_resource.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('quiz_resource.columns.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn (Quiz $record) => $record->attempts()->count() === 0)
                    ->tooltip(fn (Quiz $record) => $record->attempts()->count() > 0 ? 'Không thể chỉnh sửa quiz đã có người làm bài' : null),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Quiz $record) => $record->attempts()->count() === 0)
                    ->tooltip(fn (Quiz $record) => $record->attempts()->count() > 0 ? 'Không thể xóa quiz đã có người làm bài' : null),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function ($records) {
                            $recordsWithAttempts = $records->filter(fn ($record) => $record->attempts()->count() > 0);
                            if ($recordsWithAttempts->count() > 0) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Không thể xóa')
                                    ->body('Một số quiz đã có người làm bài và không thể xóa.')
                                    ->danger()
                                    ->send();

                                return;
                            }
                            $records->each->delete();
                        }),
                    ExportBulkAction::make()->exports([
                        ExcelExport::make()
                            ->queue()
                            ->askForFilename()
                            ->askForWriterType(),
                    ]),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\QuestionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'view' => Pages\ViewQuiz::route('/{record}'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
        ];
    }
}

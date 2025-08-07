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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use HayderHatem\FilamentExcelImport\Actions\Concerns\CanImportExcelRecords;
use App\Filament\Imports\QuizImporter;

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
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->label(__('quiz_resource.fields.title'))
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label(__('quiz_resource.fields.description'))
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('max_points')
                    ->label(__('quiz_resource.fields.max_points'))
                    ->required()
                    ->numeric()
                    ->default(100),
                Forms\Components\TextInput::make('max_attempts')
                    ->label(__('quiz_resource.fields.max_attempts'))
                    ->numeric(),
                Forms\Components\Toggle::make('is_single_session')
                    ->label(__('quiz_resource.fields.is_single_session'))
                    ->required(),
                Forms\Components\TextInput::make('time_limit_minutes')
                    ->label(__('quiz_resource.fields.time_limit_minutes'))
                    ->numeric(),
                Forms\Components\DateTimePicker::make('start_at')
                    ->label(__('quiz_resource.fields.start_at'))
                    ->required(),
                Forms\Components\DateTimePicker::make('end_at')
                    ->label(__('quiz_resource.fields.end_at'))
                    ->required(),
            ]);
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            //
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

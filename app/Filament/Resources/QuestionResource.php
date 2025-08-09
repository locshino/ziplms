<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use HayderHatem\FilamentExcelImport\Actions\Concerns\CanImportExcelRecords;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class QuestionResource extends Resource
{
    use CanImportExcelRecords;

    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Quản lý';

    protected static ?int $navigationSort = 5;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('quiz_id')
                    ->label(__('question_resource.fields.quiz_id'))
                    ->relationship('quiz', 'title')
                    ->required(),
                Forms\Components\Textarea::make('title')
                    ->label(__('question_resource.fields.title'))
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('points')
                    ->label(__('question_resource.fields.points'))
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\Toggle::make('is_multiple_response')
                    ->label(__('question_resource.fields.is_multiple_response'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('question_resource.columns.id'))
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('quiz.title')
                    ->label(__('question_resource.columns.quiz_title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('points')
                    ->label(__('question_resource.columns.points'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_multiple_response')
                    ->label(__('question_resource.columns.is_multiple_response'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('question_resource.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('question_resource.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('question_resource.columns.deleted_at'))
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
            RelationManagers\AnswerChoicesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'view' => Pages\ViewQuestion::route('/{record}'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnswerChoiceResource\Pages;
use App\Models\AnswerChoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use HayderHatem\FilamentExcelImport\Actions\Concerns\CanImportExcelRecords;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class AnswerChoiceResource extends Resource
{
    use CanImportExcelRecords;

    protected static ?string $model = AnswerChoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationGroup = 'Quản lý';

    protected static ?int $navigationSort = 6;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('question_id')
                    ->label(__('answer_choice_resource.fields.question_id'))
                    ->relationship('question', 'title')
                    ->required(),
                Forms\Components\Textarea::make('title')
                    ->label(__('answer_choice_resource.fields.title'))
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_correct')
                    ->label(__('answer_choice_resource.fields.is_correct'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('answer_choice_resource.columns.id'))
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('question.title')
                    ->label(__('answer_choice_resource.columns.question_title'))
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_correct')
                    ->label(__('answer_choice_resource.columns.is_correct'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('answer_choice_resource.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('answer_choice_resource.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('answer_choice_resource.columns.deleted_at'))
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
            'index' => Pages\ListAnswerChoices::route('/'),
            'create' => Pages\CreateAnswerChoice::route('/create'),
            'view' => Pages\ViewAnswerChoice::route('/{record}'),
            'edit' => Pages\EditAnswerChoice::route('/{record}/edit'),
        ];
    }
}

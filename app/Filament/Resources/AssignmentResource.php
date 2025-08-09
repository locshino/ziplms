<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssignmentResource\Pages;
use App\Filament\Resources\AssignmentResource\RelationManagers;
use App\Models\Assignment;
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
use App\Filament\Imports\AssignmentImporter;

class AssignmentResource extends Resource
{
    use CanImportExcelRecords;
    protected static ?string $model = Assignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationGroup = 'Quản lý';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->label(__('assignment_resource.fields.course_id'))
                    ->relationship('course', 'title')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->label(__('assignment_resource.fields.title'))
                    ->required(),
                Forms\Components\Textarea::make('instructions')
                    ->label(__('assignment_resource.fields.instructions'))
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('max_points')
                    ->label(__('assignment_resource.fields.max_points'))
                    ->required()
                    ->numeric()
                    ->default(100),
                Forms\Components\TextInput::make('late_penalty_percentage')
                    ->label(__('assignment_resource.fields.late_penalty_percentage'))
                    ->numeric(),
                Forms\Components\DateTimePicker::make('start_at')
                    ->label(__('assignment_resource.fields.start_at'))
                    ->required(),
                Forms\Components\DateTimePicker::make('due_at')
                    ->label(__('assignment_resource.fields.due_at'))
                    ->required(),
                Forms\Components\DateTimePicker::make('grading_at')
                    ->label(__('assignment_resource.fields.grading_at'))
                    ->required(),
                Forms\Components\DateTimePicker::make('end_at')
                    ->label(__('assignment_resource.fields.end_at'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('assignment_resource.columns.id'))
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('course.title')
                    ->label(__('assignment_resource.columns.course_title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('assignment_resource.columns.title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('max_points')
                    ->label(__('assignment_resource.columns.max_points'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('late_penalty_percentage')
                    ->label(__('assignment_resource.columns.late_penalty_percentage'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_at')
                    ->label(__('assignment_resource.columns.start_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_at')
                    ->label(__('assignment_resource.columns.due_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grading_at')
                    ->label(__('assignment_resource.columns.grading_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_at')
                    ->label(__('assignment_resource.columns.end_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('assignment_resource.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('assignment_resource.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('assignment_resource.columns.deleted_at'))
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
            'index' => Pages\ListAssignments::route('/'),
            'create' => Pages\CreateAssignment::route('/create'),
            'view' => Pages\ViewAssignment::route('/{record}'),
            'edit' => Pages\EditAssignment::route('/{record}/edit'),
        ];
    }
}

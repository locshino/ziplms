<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubmissionResource\Pages;
use App\Filament\Resources\SubmissionResource\RelationManagers;
use App\Models\Submission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use HayderHatem\FilamentExcelImport\Actions\ImportAction;
use HayderHatem\FilamentExcelImport\Actions\ImportField;

class SubmissionResource extends Resource
{
    protected static ?string $model = Submission::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';
    
    protected static ?string $navigationGroup = 'Quản lý';
    
    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('assignment_id')
                    ->label(__('submission_resource.fields.assignment_id'))
                    ->relationship('assignment', 'title')
                    ->required(),
                Forms\Components\Select::make('student_id')
                    ->label(__('submission_resource.fields.student_id'))
                    ->relationship('student', 'name')
                    ->required(),
                Forms\Components\TextInput::make('grade')
                    ->label(__('submission_resource.fields.grade'))
                    ->numeric(),
                Forms\Components\Textarea::make('feedback')
                    ->label(__('submission_resource.fields.feedback'))
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('submitted_at')
                    ->label(__('submission_resource.fields.submitted_at'))
                    ->required(),
                Forms\Components\TextInput::make('graded_by')
                    ->label(__('submission_resource.fields.graded_by')),
                Forms\Components\DateTimePicker::make('graded_at')
                    ->label(__('submission_resource.fields.graded_at')),
                Forms\Components\TextInput::make('version')
                    ->label(__('submission_resource.fields.version'))
                    ->required()
                    ->numeric()
                    ->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('submission_resource.columns.id'))
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('assignment.title')
                    ->label(__('submission_resource.columns.assignment_title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->label(__('submission_resource.columns.student_name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('grade')
                    ->label(__('submission_resource.columns.grade'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('submitted_at')
                    ->label(__('submission_resource.columns.submitted_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('graded_by')
                    ->label(__('submission_resource.columns.graded_by'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('graded_at')
                    ->label(__('submission_resource.columns.graded_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('version')
                    ->label(__('submission_resource.columns.version'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('submission_resource.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('submission_resource.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('submission_resource.columns.deleted_at'))
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
            'index' => Pages\ListSubmissions::route('/'),
            'create' => Pages\CreateSubmission::route('/create'),
            'view' => Pages\ViewSubmission::route('/{record}'),
            'edit' => Pages\EditSubmission::route('/{record}/edit'),
        ];
    }
}

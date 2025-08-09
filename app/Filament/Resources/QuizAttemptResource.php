<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizAttemptResource\Pages;
use App\Filament\Resources\QuizAttemptResource\RelationManagers;
use App\Models\QuizAttempt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class QuizAttemptResource extends Resource
{
    protected static ?string $model = QuizAttempt::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Quản lý';

    protected static ?int $navigationSort = 13;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('quiz_id')
                    ->label(__('quiz_attempt_resource.fields.quiz_id'))
                    ->relationship('quiz', 'title')
                    ->required(),
                Forms\Components\Select::make('student_id')
                    ->label(__('quiz_attempt_resource.fields.student_id'))
                    ->relationship('student', 'name')
                    ->required(),
                Forms\Components\TextInput::make('attempt_number')
                    ->label(__('quiz_attempt_resource.fields.attempt_number'))
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('score')
                    ->label(__('quiz_attempt_resource.fields.score'))
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->label(__('quiz_attempt_resource.fields.status'))
                    ->required(),
                Forms\Components\DateTimePicker::make('started_at')
                    ->label(__('quiz_attempt_resource.fields.started_at'))
                    ->required(),
                Forms\Components\DateTimePicker::make('completed_at')
                    ->label(__('quiz_attempt_resource.fields.completed_at')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('quiz_attempt_resource.columns.id'))
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('quiz.title')
                    ->label(__('quiz_attempt_resource.columns.quiz_title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->label(__('quiz_attempt_resource.columns.student_name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('attempt_number')
                    ->label(__('quiz_attempt_resource.columns.attempt_number'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('score')
                    ->label(__('quiz_attempt_resource.columns.score'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('quiz_attempt_resource.columns.status'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('started_at')
                    ->label(__('quiz_attempt_resource.columns.started_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label(__('quiz_attempt_resource.columns.completed_at'))
                    ->dateTime()
                    ->sortable(),
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
            RelationManagers\StudentQuizAnswersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizAttempts::route('/'),
            'create' => Pages\CreateQuizAttempt::route('/create'),
            'view' => Pages\ViewQuizAttempt::route('/{record}'),
            'edit' => Pages\EditQuizAttempt::route('/{record}/edit'),
        ];
    }
}

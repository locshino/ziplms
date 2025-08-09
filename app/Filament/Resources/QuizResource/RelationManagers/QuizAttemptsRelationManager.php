<?php

namespace App\Filament\Resources\QuizResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QuizAttemptsRelationManager extends RelationManager
{
    protected static string $relationship = 'attempts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->label('Học sinh')
                    ->relationship('student', 'name')
                    ->required(),
                Forms\Components\TextInput::make('attempt_number')
                    ->label('Lần thử')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('score')
                    ->label('Điểm')
                    ->numeric(),
                Forms\Components\Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'in_progress' => 'Đang làm',
                        'completed' => 'Hoàn thành',
                        'submitted' => 'Đã nộp',
                        'paused' => 'Tạm dừng',
                    ])
                    ->required(),
                Forms\Components\DateTimePicker::make('started_at')
                    ->label('Bắt đầu lúc')
                    ->required(),
                Forms\Components\DateTimePicker::make('completed_at')
                    ->label('Hoàn thành lúc'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('student.name')
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Học sinh')
                    ->searchable(),
                Tables\Columns\TextColumn::make('attempt_number')
                    ->label('Lần thử')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('score')
                    ->label('Điểm')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->colors([
                        'warning' => 'in_progress',
                        'success' => 'completed',
                        'primary' => 'submitted',
                        'secondary' => 'paused',
                    ]),
                Tables\Columns\TextColumn::make('started_at')
                    ->label('Bắt đầu')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Hoàn thành')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'in_progress' => 'Đang làm',
                        'completed' => 'Hoàn thành',
                        'submitted' => 'Đã nộp',
                        'paused' => 'Tạm dừng',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

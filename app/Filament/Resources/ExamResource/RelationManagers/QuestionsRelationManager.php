<?php

namespace App\Filament\Resources\ExamResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';
    protected static ?string $recordTitleAttribute = 'question_text';
    protected static ?string $label = 'Câu hỏi trong bài kiểm tra';

    public function form(Form $form): Form
    {
        // Form này dùng khi Sửa điểm/thứ tự của một câu hỏi đã được thêm
        return $form
            ->schema([
                Forms\Components\TextInput::make('points')
                    ->label('Điểm cho câu hỏi này')
                    ->numeric()
                    ->required()
                    ->default(1.00),
                Forms\Components\TextInput::make('question_order')
                    ->label('Thứ tự câu hỏi')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question_text')
                    ->label('Nội dung câu hỏi')
                    ->limit(80)
                    ->wrap()
                    ->getStateUsing(fn($record): ?string => $record->getTranslation('question_text', app()->getLocale())),

                Tables\Columns\TextColumn::make('points')->label('Điểm')->sortable(),
                Tables\Columns\TextColumn::make('question_order')->label('Thứ tự')->sortable(),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\TextInput::make('points')->label('Điểm')->numeric()->required()->default(1.00),
                        Forms\Components\TextInput::make('question_order')->label('Thứ tự')->numeric()->default(0),
                    ])
                    ->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}

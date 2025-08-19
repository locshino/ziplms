<?php

namespace App\Filament\Resources\QuizResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('title')
                    ->label('Câu hỏi')
                    ->required()
                    ->columnSpanFull(),
                Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('points')
                            ->label('Điểm')
                            ->required()
                            ->numeric()
                            ->default(1),
                        Forms\Components\Toggle::make('is_multiple_response')
                            ->label('Nhiều lựa chọn')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                // Reset tất cả đáp án đúng khi thay đổi loại câu hỏi
                                $answerChoices = $get('answerChoices') ?? [];
                                foreach ($answerChoices as $index => $choice) {
                                    $set("answerChoices.{$index}.is_correct", false);
                                }
                            }),
                    ]),
                Repeater::make('answerChoices')
                    ->label('Các lựa chọn đáp án')
                    ->relationship()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Lựa chọn')
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\Toggle::make('is_correct')
                                    ->label('Đáp án đúng')
                                    ->required()
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->minItems(2)
                    ->maxItems(10)
                    ->defaultItems(4)
                    ->columnSpanFull()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Lựa chọn mới')
                    ->addActionLabel('Thêm lựa chọn')
                    ->rules([
                        function (callable $get) {
                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                $isMultipleResponse = $get('is_multiple_response') ?? false;
                                $correctAnswers = collect($value)->where('is_correct', true)->count();

                                if ($isMultipleResponse && $correctAnswers < 2) {
                                    $fail('Khi chọn nhiều đáp án đúng, phải có ít nhất 2 đáp án được chọn.');
                                } elseif (! $isMultipleResponse && $correctAnswers !== 1) {
                                    $fail('Khi không chọn nhiều đáp án đúng, phải có đúng 1 đáp án được chọn.');
                                }
                            };
                        },
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Câu hỏi')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('points')
                    ->label('Điểm')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_multiple_response')
                    ->label('Nhiều lựa chọn')
                    ->boolean(),
                Tables\Columns\TextColumn::make('answerChoices_count')
                    ->label('Số lựa chọn')
                    ->counts('answerChoices')
                    ->badge(),
                Tables\Columns\TextColumn::make('correct_answers_count')
                    ->label('Đáp án đúng')
                    ->getStateUsing(function ($record) {
                        return $record->answerChoices()->where('is_correct', true)->count();
                    })
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_multiple_response')
                    ->label('Nhiều lựa chọn'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Thêm câu hỏi'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Xem'),
                Tables\Actions\EditAction::make()
                    ->label('Sửa'),
                Tables\Actions\DeleteAction::make()
                    ->label('Xóa'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Xóa đã chọn'),
                ]),
            ])
            ->emptyStateHeading('Chưa có câu hỏi nào')
            ->emptyStateDescription('Thêm câu hỏi đầu tiên cho quiz này.')
            ->emptyStateIcon('heroicon-o-question-mark-circle');
    }
}

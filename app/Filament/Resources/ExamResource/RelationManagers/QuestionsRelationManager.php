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

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question_text')
                    ->label('Nội dung câu hỏi')
                    ->limit(80)
                    ->wrap()
                    ->getStateUsing(fn($record): ?string => $record->getTranslation('question_text', app()->getLocale())),

                // Các cột này đọc dữ liệu từ bảng trung gian (exam_questions)
                Tables\Columns\TextColumn::make('points')->label('Điểm')->sortable(),
                Tables\Columns\TextColumn::make('question_order')->label('Thứ tự')->sortable(),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->successNotificationTitle('Câu hỏi đã được thêm vào bài thi.')
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\TextInput::make('points')->label('Điểm')->numeric()->required()->default(1.00),
                        Forms\Components\TextInput::make('question_order')->label('Thứ tự')->numeric()->default(0),
                    ])
                    ->preloadRecordSelect(),
            ])
            ->actions([
                // ==> ĐÂY LÀ PHẦN SỬA LỖI <==
                // Cung cấp một form trực tiếp cho EditAction để nó biết cách
                // cập nhật dữ liệu trên bảng trung gian (exam_questions).
                Tables\Actions\EditAction::make()
                    ->successNotificationTitle('Thông tin câu hỏi đã được cập nhật.')
                    ->form([
                        Forms\Components\TextInput::make('points')
                            ->label('Điểm cho câu hỏi này')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('question_order')
                            ->label('Thứ tự câu hỏi')
                            ->numeric(),
                    ]),

                Tables\Actions\DetachAction::make()
                    ->successNotificationTitle('Câu hỏi đã được gỡ khỏi bài thi.'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->successNotificationTitle('Các câu hỏi đã chọn đã được gỡ khỏi bài thi.'),
                ]),
            ]);
    }
}

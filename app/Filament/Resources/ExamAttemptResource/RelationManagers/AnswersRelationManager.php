<?php

namespace App\Filament\Resources\ExamAttemptResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';
    protected static ?string $label = 'Chi tiết bài làm';

    public function form(Form $form): Form
    {
        // Form này dùng để chấm điểm câu tự luận
        return $form
            ->schema([
                Forms\Components\Textarea::make('teacher_feedback')
                    ->label('Phản hồi của giáo viên')
                    ->translatable()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('points_earned')
                    ->label('Điểm đạt được')
                    ->numeric()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question.question_text')
                    ->label('Câu hỏi')
                    ->limit(50)
                    ->wrap()
                    ->getStateUsing(fn($record): ?string => $record->question?->getTranslation('question_text', app()->getLocale())),

                Tables\Columns\TextColumn::make('student_answer')
                    ->label('Câu trả lời của HS')
                    ->limit(50)
                    ->wrap()
                    ->formatStateUsing(function ($record) {
                        if ($record->selectedChoice) {
                            return $record->selectedChoice->getTranslation('choice_text', app()->getLocale());
                        }
                        if ($record->answer_text) {
                            return $record->getTranslation('answer_text', app()->getLocale());
                        }
                        return 'Không trả lời';
                    }),

                // CỘT "KẾT QUẢ" ĐÃ SỬA
                Tables\Columns\IconColumn::make('is_correct')
                    ->label('Kết quả')
                    // Dùng hàm để quyết định icon
                    ->icon(fn($state): string => match ($state) {
                        true => 'heroicon-o-check-circle',
                        false => 'heroicon-o-x-circle',
                        null => 'heroicon-o-clock',
                    })
                    // Dùng hàm để quyết định màu sắc
                    ->color(fn($state): string => match ($state) {
                        true => 'success',
                        false => 'danger',
                        null => 'warning',
                    }),

                Tables\Columns\TextColumn::make('points_earned')
                    ->label('Điểm nhận được')
                    ->placeholder('Chưa chấm'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Chấm điểm')->icon('heroicon-o-pencil-square'),
            ]);
    }

    // Vô hiệu hóa các hành động không cần thiết
    public function canCreate(): bool
    {
        return false;
    }
    public function canDelete(Model $record): bool
    {
        return false;
    }
    public function canDeleteAny(): bool
    {
        return false;
    }
}

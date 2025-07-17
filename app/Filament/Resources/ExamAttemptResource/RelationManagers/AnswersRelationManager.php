<?php

namespace App\Filament\Resources\ExamAttemptResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder; // <-- Thêm import này
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
                Forms\Components\Tabs::make('FeedbackTranslations')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Phản hồi (Tiếng Việt)')
                            ->schema([
                                Forms\Components\Textarea::make('teacher_feedback.vi')
                                    ->label(false),
                            ]),
                        Forms\Components\Tabs\Tab::make('Feedback (English)')
                            ->schema([
                                Forms\Components\Textarea::make('teacher_feedback.en')
                                    ->label(false),
                            ]),
                    ])->columnSpanFull(),

                Forms\Components\TextInput::make('points_earned')
                    ->label('Điểm đạt được')
                    ->numeric()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            // V THÊM DÒNG NÀY ĐỂ TẢI TRƯỚC DỮ LIỆU
            ->modifyQueryUsing(fn(Builder $query) => $query->with(['question', 'selectedChoice']))
            ->columns([
                Tables\Columns\TextColumn::make('question.question_text')
                    ->label('Câu hỏi')
                    ->wrap(),

                Tables\Columns\TextColumn::make('student_answer')
                    ->label('Câu trả lời của HS')
                    ->wrap()
                    ->getStateUsing(function (Model $record): string {
                        // 1. Ưu tiên hiển thị câu trả lời trắc nghiệm
                        if ($record->selectedChoice) {
                            // Giờ đây $record->selectedChoice sẽ không còn là null
                            return $record->selectedChoice->choice_text;
                        }

                        // 2. Nếu không có, hiển thị câu trả lời tự luận
                        if ($record->answer_text) {
                            return $record->answer_text;
                        }

                        // 3. Nếu không có cả hai, hiển thị gạch ngang
                        return '—';
                    }),

                Tables\Columns\IconColumn::make('is_correct')
                    ->label('Kết quả')
                    ->icon(fn($state): string => match ($state) {
                        true => 'heroicon-o-check-circle',
                        false => 'heroicon-o-x-circle',
                        null => 'heroicon-o-clock',
                    })
                    ->color(fn($state): string => match ($state) {
                        true => 'success',
                        false => 'danger',
                        null => 'warning',
                    }),

                Tables\Columns\TextColumn::make('points_earned')->label('Điểm nhận được')->placeholder('Chưa chấm'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Chấm điểm')->icon('heroicon-o-pencil-square')
                    ->successNotificationTitle('Điểm cho câu trả lời đã được cập nhật.'),
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

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
                // SỬ DỤNG TABS ĐỂ TẠO GIAO DIỆN ĐA NGÔN NGỮ
                Forms\Components\Tabs::make('FeedbackTranslations')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Phản hồi (Tiếng Việt)')
                            ->schema([
                                // Dùng dot notation để liên kết với key 'vi' trong cột JSON
                                Forms\Components\Textarea::make('teacher_feedback.vi')
                                    ->label(false), // Ẩn label vì đã có ở Tab
                            ]),
                        Forms\Components\Tabs\Tab::make('Feedback (English)')
                            ->schema([
                                // Dùng dot notation để liên kết với key 'en' trong cột JSON
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
            ->columns([
                Tables\Columns\TextColumn::make('question.question_text')
                    ->label('Câu hỏi')
                    ->wrap()
                    ->getStateUsing(fn (Model $record): ?string => $record->question?->getTranslation('question_text', app()->getLocale())),

                Tables\Columns\TextColumn::make('student_answer')
                    ->label('Câu trả lời của HS')
                    ->wrap() // Cho phép xuống dòng nếu câu trả lời quá dài
                    ->getStateUsing(function (Model $record): string {
                        // 1. Ưu tiên hiển thị câu trả lời trắc nghiệm (nếu có)
                        if ($record->selectedChoice) {
                            return $record->selectedChoice->getTranslation('choice_text', app()->getLocale())
                                ?? $record->selectedChoice->getTranslation('choice_text', 'vi') // Fallback về Tiếng Việt
                                ?? '—';
                        }

                        // 2. Hiển thị câu trả lời tự luận
                        // Dữ liệu của bạn: "answer_text" => ["vi" => "Nội dung câu trả lời."]
                        // getTranslation sẽ tự động tìm key ngôn ngữ hiện tại (vd: 'vi') và lấy giá trị tương ứng.
                        $answer = $record->getTranslation('answer_text', app()->getLocale());

                        // Nếu không có bản dịch cho ngôn ngữ hiện tại, thử lấy bản dịch Tiếng Việt làm mặc định
                        if (empty($answer)) {
                            $answer = $record->getTranslation('answer_text', 'vi');
                        }

                        // Nếu vẫn không có câu trả lời nào, hiển thị ký tự gạch ngang
                        return ! empty($answer) ? $answer : '—';
                    }),

                Tables\Columns\IconColumn::make('is_correct')
                    ->label('Kết quả')
                    ->icon(fn ($state): string => match ($state) {
                        true => 'heroicon-o-check-circle',
                        false => 'heroicon-o-x-circle',
                        null => 'heroicon-o-clock',
                    })
                    ->color(fn ($state): string => match ($state) {
                        true => 'success',
                        false => 'danger',
                        null => 'warning',
                    }),

                Tables\Columns\TextColumn::make('points_earned')->label('Điểm nhận được')->placeholder('Chưa chấm'),
            ])
            ->actions([
                // Bỏ mutateRecordDataUsing đi vì không cần nữa
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

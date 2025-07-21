<?php

namespace App\Filament\Resources\ExamAttemptResource\RelationManagers;

use App\Enums\QuestionType;
use App\Models\QuestionChoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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
            // Tải trước các quan hệ để tăng hiệu suất và lấy được loại câu hỏi
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['question.tags', 'selectedChoice']))
            ->columns([
                Tables\Columns\TextColumn::make('question.question_text')
                    ->label('Câu hỏi')
                    ->wrap(),

                Tables\Columns\TextColumn::make('student_answer')
                    ->label('Câu trả lời của HS')
                    ->wrap()
                    ->getStateUsing(function (Model $record): string {
                        // Lấy loại câu hỏi từ a question relationship đã tải trước
                        $questionTypeTag = $record->question?->tagsWithType(QuestionType::key())->first();
                        $questionType = $questionTypeTag ? QuestionType::tryFrom($questionTypeTag->name) : null;

                        if (! $questionType) {
                            return 'Không xác định được loại câu hỏi';
                        }

                        switch ($questionType) {
                            case QuestionType::SingleChoice:
                            case QuestionType::TrueFalse:
                                return $record->selectedChoice->choice_text ?? '—';

                            case QuestionType::MultipleChoice:
                                if (empty($record->chosen_option_ids)) {
                                    return '—';
                                }
                                // Lấy nội dung text của các lựa chọn đã chọn
                                $choices = QuestionChoice::whereIn('id', $record->chosen_option_ids)->pluck('choice_text');

                                return $choices->isNotEmpty() ? $choices->implode(', ') : '—';

                            case QuestionType::ShortAnswer:
                            case QuestionType::Essay:
                            case QuestionType::FillBlank:
                                // Lấy tất cả các bản dịch có sẵn cho câu trả lời
                                $translations = $record->getTranslations('answer_text');
                                if (empty($translations)) {
                                    return '—';
                                }

                                // Ưu tiên ngôn ngữ hiện tại, nếu không có thì lấy bản dịch đầu tiên (ngôn ngữ gốc)
                                $studentAnswer = $record->answer_text ?: reset($translations);

                                // Định dạng lại cho câu hỏi điền vào chỗ trống
                                if ($questionType === QuestionType::FillBlank) {
                                    return str_replace('|', ' | ', $studentAnswer);
                                }

                                return $studentAnswer;

                            default:
                                return '—';
                        }
                    }),

                Tables\Columns\IconColumn::make('is_correct')
                    ->label('Kết quả')
                    ->icon(fn ($state): string => match ($state) {
                        true => 'heroicon-o-check-circle',
                        false => 'heroicon-o-x-circle',
                        default => 'heroicon-o-clock', // Dùng default thay cho null
                    })
                    ->color(fn ($state): string => match ($state) {
                        true => 'success',
                        false => 'danger',
                        default => 'warning', // Dùng default thay cho null
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

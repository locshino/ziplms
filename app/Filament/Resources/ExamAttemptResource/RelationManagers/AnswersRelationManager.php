<?php

namespace App\Filament\Resources\ExamAttemptResource\RelationManagers;

use App\Enums\ExamShowResultsType;
use App\Enums\QuestionType;
use App\Models\ExamQuestion;
use App\Models\QuestionChoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\ExamAttemptResource\Pages\ViewExamAttempt;
use App\Filament\Resources\ExamAttemptResource;
use Illuminate\Support\Arr;

class AnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';
    protected static ?string $label = 'Chi tiết bài làm';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('answers-relation-manager.label');
    }

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->with(['question.tags', 'question.choices', 'selectedChoice', 'attempt.exam']))
            ->columns([
                Tables\Columns\TextColumn::make('question.question_text')->label(__('answers-relation-manager.table.columns.question'))->wrap()->url(fn(?Model $record): ?string => $record ? ExamAttemptResource::getUrl('review', ['record' => $record->id]) : null)->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('student_answer')->label(__('answers-relation-manager.table.columns.student_answer'))->wrap()->getStateUsing(function (?Model $record): string {
                    if (!$record || !$record->question) return '—';
                    $questionTypeTag = $record->question->tagsWithType(QuestionType::key())->first();
                    $questionType = $questionTypeTag ? QuestionType::tryFrom($questionTypeTag->name) : null;
                    if (!$questionType) {
                        return __('answers-relation-manager.table.errors.unknown_question_type');
                    }
                    switch ($questionType) {
                        case QuestionType::SingleChoice:
                        case QuestionType::TrueFalse:
                            return $record->selectedChoice->choice_text ?? '—';
                        case QuestionType::MultipleChoice:
                            if (empty($record->chosen_option_ids)) return '—';
                            $choices = QuestionChoice::whereIn('id', Arr::flatten($record->chosen_option_ids))->pluck('choice_text');
                            return $choices->isNotEmpty() ? $choices->implode(', ') : '—';
                        case QuestionType::ShortAnswer:
                        case QuestionType::Essay:
                        case QuestionType::FillBlank:
                            $studentAnswer = $record->answer_text;
                            if (empty($studentAnswer)) return '—';
                            if ($questionType === QuestionType::FillBlank) {
                                return str_replace('|', ' | ', $studentAnswer);
                            }
                            return $studentAnswer;
                        default:
                            return '—';
                    }
                }),
                Tables\Columns\TextColumn::make('correct_answer')->label(__('answers-relation-manager.table.columns.correct_answer'))->wrap()->getStateUsing(function (?Model $record): string {
                    if (!$record || !$record->question) return '—';
                    $question = $record->question;
                    $questionTypeTag = $question->tagsWithType(QuestionType::key())->first();
                    $questionType = $questionTypeTag ? QuestionType::tryFrom($questionTypeTag->name) : null;
                    if (!$questionType) return '—';
                    switch ($questionType) {
                        case QuestionType::SingleChoice:
                        case QuestionType::TrueFalse:
                            $correctChoice = $question->choices->firstWhere('is_correct', true);
                            return $correctChoice?->choice_text ?? __('answers-relation-manager.table.placeholders.no_correct_answer_defined');
                        case QuestionType::MultipleChoice:
                            $correctChoices = $question->choices->where('is_correct', true)->pluck('choice_text');
                            return $correctChoices->isNotEmpty() ? $correctChoices->implode(', ') : __('answers-relation-manager.table.placeholders.no_correct_answer_defined');
                        case QuestionType::ShortAnswer:
                        case QuestionType::Essay:
                        case QuestionType::FillBlank:
                            $correctAnswer = $question->correct_answer;
                            if (empty($correctAnswer)) return __('answers-relation-manager.table.placeholders.no_correct_answer_defined');
                            if ($questionType === QuestionType::FillBlank) {
                                return str_replace('|', ' | ', $correctAnswer);
                            }
                            return $correctAnswer;
                        default:
                            return '—';
                    }
                })->visible(fn(?Model $record): bool => $record && !is_null($record->is_correct)),
                Tables\Columns\IconColumn::make('is_correct')->label(__('answers-relation-manager.table.columns.result'))->icon(fn($state): string => match ($state) {
                    true => 'heroicon-o-check-circle',
                    false => 'heroicon-o-x-circle',
                    default => 'heroicon-o-clock',
                })->color(fn($state): string => match ($state) {
                    true => 'success',
                    false => 'danger',
                    default => 'warning',
                }),
                Tables\Columns\TextColumn::make('points_earned')->label(__('answers-relation-manager.table.columns.points_earned'))->placeholder(__('answers-relation-manager.table.placeholders.not_graded')),
                Tables\Columns\TextColumn::make('teacher_feedback')->label(__('answers-relation-manager.table.columns.teacher_feedback'))->placeholder(__('answers-relation-manager.table.placeholders.no_feedback'))->limit(50)->wrap()->visible(fn(RelationManager $livewire): bool => $livewire->pageClass === ViewExamAttempt::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(fn(?Model $record): string => !is_null($record?->points_earned) ? __('answers-relation-manager.actions.view_result') : __('answers-relation-manager.actions.grade'))
                    ->icon(fn(?Model $record): string => !is_null($record?->points_earned) ? 'heroicon-o-eye' : 'heroicon-o-pencil-square')
                    ->successNotificationTitle(__('answers-relation-manager.actions.grade_success_notification'))
                    ->form(function (?Model $record, RelationManager $livewire): array {
                        if (!$record) return [];
                        $exam = $livewire->getOwnerRecord()->exam;
                        $examQuestion = ExamQuestion::find($record->exam_question_id);
                        $maxPoints = $examQuestion?->points ?? 0;

                        return [
                            Forms\Components\Tabs::make('FeedbackTranslations')->tabs([
                                Forms\Components\Tabs\Tab::make(__('answers-relation-manager.form.tabs.feedback_vi'))->schema([Forms\Components\Textarea::make('teacher_feedback.vi')->label(false),]),
                                Forms\Components\Tabs\Tab::make(__('answers-relation-manager.form.tabs.feedback_en'))->schema([Forms\Components\Textarea::make('teacher_feedback.en')->label(false),]),
                            ])->columnSpanFull(),

                            Forms\Components\Toggle::make('is_correct')
                                ->label('Câu trả lời này đúng?')
                                ->visible($exam->show_results_after === ExamShowResultsType::MANUAL)
                                ->reactive(), // Thêm reactive để form phản ứng lại thay đổi

                            Forms\Components\TextInput::make('points_earned')
                                ->label(__('answers-relation-manager.form.points_earned'))
                                ->hint(__('answers-relation-manager.form.max_points_hint', ['points' => $maxPoints]))
                                ->rules(['required', 'numeric', 'max:' . $maxPoints])
                                ->validationMessages(['max' => __('answers-relation-manager.validation.points_exceeded')])
                                // ▼▼▼ ĐÃ SỬA: Logic mới để vô hiệu hóa và đặt lại điểm ▼▼▼
                                ->disabled(function (callable $get) use ($exam) {
                                    // Vô hiệu hóa nếu bài thi là chấm thủ công VÀ câu trả lời bị đánh dấu là sai
                                    return $exam->show_results_after === ExamShowResultsType::MANUAL && !$get('is_correct');
                                })
                                ->afterStateUpdated(function (callable $get, callable $set) {
                                    // Nếu is_correct bị tắt, tự động đặt điểm về 0
                                    if (!$get('is_correct')) {
                                        $set('points_earned', 0);
                                    }
                                }),
                        ];
                    })
                    ->mutateRecordDataUsing(function (Model $record, array $data): array {
                        if (is_null($data['points_earned'])) {
                            $examQuestion = ExamQuestion::find($record->exam_question_id);
                            $data['points_earned'] = $examQuestion?->points;
                        }
                        return $data;
                    })
                    ->after(function (RelationManager $livewire) {
                        $attempt = $livewire->getOwnerRecord();
                        if ($attempt) {
                            $attempt->recalculateAndFinalize();
                        }
                    }),
            ]);
    }
}

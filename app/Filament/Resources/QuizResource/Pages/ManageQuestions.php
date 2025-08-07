<?php

namespace App\Filament\Resources\QuizResource\Pages;

use App\Filament\Resources\QuizResource;
use App\Models\Question;
use App\Models\AnswerChoice;
use App\Services\Interfaces\QuestionServiceInterface;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Get;
use Illuminate\Contracts\Support\Htmlable;

class ManageQuestions extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = QuizResource::class;

    protected static string $view = 'filament.resources.quiz-resource.pages.manage-questions';

    public $quiz;
    protected ?QuestionServiceInterface $questionService = null;

    protected function getQuestionService(): QuestionServiceInterface
    {
        if ($this->questionService === null) {
            $this->questionService = app(QuestionServiceInterface::class);
        }
        return $this->questionService;
    }

    public function mount($record): void
    {
        $this->quiz = $this->getResource()::resolveRecordRouteBinding($record);
    }

    public function getTitle(): string | Htmlable
    {
        return "Quản lý câu hỏi: {$this->quiz->title}";
    }

    public function getBreadcrumbs(): array
    {
        return [
            url()->route('filament.app.resources.quizzes.index') => 'Quiz',
            '#' => $this->getTitle(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('createQuestion')
                ->label('Thêm câu hỏi')
                ->model(Question::class)
                ->form([
                    Forms\Components\TextInput::make('title')
                        ->label('Tiêu đề câu hỏi')
                        ->required()
                        ->maxLength(1000)
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('points')
                        ->label('Điểm số')
                        ->numeric()
                        ->default(1)
                        ->minValue(0.1)
                        ->step(0.1)
                        ->required(),

                    Forms\Components\Toggle::make('is_multiple_response')
                        ->label('Cho phép nhiều lựa chọn')
                        ->default(false)
                        ->live()
                        ->afterStateUpdated(function ($state, Forms\Set $set, Get $get) {
                            // When switching from multiple to single choice, keep only the first correct answer
                            if (!$state) {
                                $answerChoices = $get('answer_choices') ?? [];
                                $firstCorrectFound = false;

                                foreach ($answerChoices as $index => $choice) {
                                    if (($choice['is_correct'] ?? false) && !$firstCorrectFound) {
                                        $firstCorrectFound = true;
                                    } elseif ($choice['is_correct'] ?? false) {
                                        $set("answer_choices.{$index}.is_correct", false);
                                    }
                                }
                            }
                        }),

                    Forms\Components\Repeater::make('answer_choices')
                        ->label('Lựa chọn đáp án')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Nội dung đáp án')
                                ->required()
                                ->maxLength(500),

                            Forms\Components\Toggle::make('is_correct')
                                ->label('Đáp án đúng')
                                ->default(false)
                                ->live()
                                ->afterStateUpdated(function ($state, Forms\Set $set, Get $get, $component) {
                                    // Only apply single-choice logic if the toggle is being turned ON
                                    if ($state && !$get('../../is_multiple_response')) {
                                        $answerChoices = $get('../../answer_choices') ?? [];
                                        $currentPath = $component->getStatePath();

                                        // Extract current index from path
                                        if (preg_match('/answer_choices\.(\d+)\.is_correct/', $currentPath, $matches)) {
                                            $currentIndex = (int) $matches[1];

                                            // Uncheck all others
                                            foreach ($answerChoices as $index => $choice) {
                                                if ($index !== $currentIndex) {
                                                    $set("../../answer_choices.{$index}.is_correct", false);
                                                }
                                            }
                                        }
                                    }
                                }),
                        ])
                        ->minItems(2)
                        ->maxItems(10)
                        ->defaultItems(2)
                        ->addActionLabel('Thêm lựa chọn')
                        ->deleteAction(
                            fn(\Filament\Forms\Components\Actions\Action $action) => $action->label('Xóa lựa chọn')
                        )
                        ->columnSpanFull()
                        ->collapsible(),
                ])
                ->action(function (array $data) {
                    // Validate answer choices before creating
                    $isMultiple = $data['is_multiple_response'] ?? false;
                    $correctAnswers = array_filter($data['answer_choices'] ?? [], fn($choice) => $choice['is_correct'] ?? false);

                    if (!$isMultiple) {
                        if (count($correctAnswers) > 1) {
                            Notification::make()
                                ->danger()
                                ->title('Lỗi câu hỏi')
                                ->body('Câu hỏi một lựa chọn chỉ được có một đáp án đúng.')
                                ->send();
                            return;
                        }
                        if (count($correctAnswers) === 0) {
                            Notification::make()
                                ->danger()
                                ->title('Lỗi câu hỏi')
                                ->body('Phải có ít nhất một đáp án đúng.')
                                ->send();
                            return;
                        }
                    } else {
                        if (count($correctAnswers) < 2) {
                            Notification::make()
                                ->danger()
                                ->title('Lỗi câu hỏi')
                                ->body('Câu hỏi nhiều lựa chọn phải có ít nhất 2 đáp án đúng.')
                                ->send();
                            return;
                        }
                    }

                    // Add quiz_id to data
                    $data['quiz_id'] = $this->quiz->id;

                    try {
                        // Use QuestionService to create question with answer choices
                        $question = $this->getQuestionService()->createWithAnswerChoices($data);

                        Notification::make()
                            ->success()
                            ->title('Câu hỏi đã được tạo')
                            ->body('Câu hỏi mới đã được thêm vào quiz.')
                            ->send();

                        // Refresh the table
                        $this->dispatch('refreshTable');
                    } catch (\App\Exceptions\Services\QuestionServiceException $e) {
                        Notification::make()
                            ->danger()
                            ->title('Lỗi')
                            ->body($e->getMessage())
                            ->send();
                    }
                }),

            Actions\Action::make('backToQuiz')
                ->label('Quay lại Quiz')
                ->url(fn() => QuizResource::getUrl('index'))
                ->color('gray'),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Question::query()->where('quiz_id', $this->quiz->id)->with('answerChoices'))
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Câu hỏi')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

                Tables\Columns\TextColumn::make('points')
                    ->label('Điểm')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('is_multiple_response')
                    ->label('Nhiều lựa chọn')
                    ->boolean()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('answer_choices_count')
                    ->label('Số lựa chọn')
                    ->counts('answerChoices')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('correct_answers')
                    ->label('Đáp án đúng')
                    ->getStateUsing(function (Question $record): string {
                        $correctChoices = $record->answerChoices->where('is_correct', true);
                        return $correctChoices->pluck('title')->join(', ');
                    })
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tạo lúc')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_multiple_response')
                    ->label('Loại câu hỏi')
                    ->placeholder('Tất cả')
                    ->trueLabel('Nhiều lựa chọn')
                    ->falseLabel('Một lựa chọn'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->fillForm(function (Question $record): array {
                        // Load question data with answer choices
                        $question = Question::with('answerChoices')->find($record->id);
                        $data = $question->toArray();

                        // Convert answer choices relationship to array format
                        if ($question->answerChoices) {
                            $data['answer_choices'] = $question->answerChoices->toArray();
                        }

                        return $data;
                    })
                    ->form([
                        Forms\Components\TextInput::make('title')
                            ->label('Tiêu đề câu hỏi')
                            ->required()
                            ->maxLength(1000)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('points')
                            ->label('Điểm số')
                            ->numeric()
                            ->minValue(0.1)
                            ->step(0.1)
                            ->required(),

                        Forms\Components\Toggle::make('is_multiple_response')
                            ->label('Cho phép nhiều lựa chọn')
                            ->default(false)
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Get $get) {
                                // When switching from multiple to single choice, keep only the first correct answer
                                if (!$state) {
                                    $answerChoices = $get('answer_choices') ?? [];
                                    $firstCorrectFound = false;

                                    foreach ($answerChoices as $index => $choice) {
                                        if (($choice['is_correct'] ?? false) && !$firstCorrectFound) {
                                            $firstCorrectFound = true;
                                        } elseif ($choice['is_correct'] ?? false) {
                                            $set("answer_choices.{$index}.is_correct", false);
                                        }
                                    }
                                }
                            }),

                        Forms\Components\Repeater::make('answer_choices')
                            ->label('Lựa chọn đáp án')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Nội dung đáp án')
                                    ->required()
                                    ->maxLength(500),

                                Forms\Components\Toggle::make('is_correct')
                                    ->label('Đáp án đúng')
                                    ->default(false)
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set, Get $get, $component) {
                                        // Only apply single-choice logic if the toggle is being turned ON
                                        if ($state && !$get('../../is_multiple_response')) {
                                            $answerChoices = $get('../../answer_choices') ?? [];
                                            $currentPath = $component->getStatePath();

                                            // Extract current index from path
                                            if (preg_match('/answer_choices\.(\d+)\.is_correct/', $currentPath, $matches)) {
                                                $currentIndex = (int) $matches[1];

                                                // Uncheck all others
                                                foreach ($answerChoices as $index => $choice) {
                                                    if ($index !== $currentIndex) {
                                                        $set("../../answer_choices.{$index}.is_correct", false);
                                                    }
                                                }
                                            }
                                        }
                                    }),
                            ])
                            ->minItems(2)
                            ->maxItems(10)
                            ->addActionLabel('Thêm lựa chọn')
                            ->deleteAction(
                                fn(\Filament\Forms\Components\Actions\Action $action) => $action->label('Xóa lựa chọn')
                            )
                            ->columnSpanFull()
                            ->collapsible(),
                    ])

                    ->action(function (Question $record, array $data) {
                        // Validate answer choices before updating
                        $isMultiple = $data['is_multiple_response'] ?? false;
                        $correctAnswers = array_filter($data['answer_choices'] ?? [], fn($choice) => $choice['is_correct'] ?? false);

                        if (!$isMultiple) {
                            if (count($correctAnswers) > 1) {
                                Notification::make()
                                    ->danger()
                                    ->title('Lỗi validation')
                                    ->body('Câu hỏi một lựa chọn chỉ được có một đáp án đúng.')
                                    ->send();
                                return;
                            }
                            if (count($correctAnswers) === 0) {
                                Notification::make()
                                    ->danger()
                                    ->title('Lỗi validation')
                                    ->body('Phải có ít nhất một đáp án đúng.')
                                    ->send();
                                return;
                            }
                        } else {
                            if (count($correctAnswers) < 2) {
                                Notification::make()
                                    ->danger()
                                    ->title('Lỗi validation')
                                    ->body('Câu hỏi nhiều lựa chọn phải có ít nhất 2 đáp án đúng.')
                                    ->send();
                                return;
                            }
                        }

                        try {
                            // Use QuestionService to update question with answer choices
                            $this->getQuestionService()->updateWithAnswerChoices($record->id, $data);

                            Notification::make()
                                ->success()
                                ->title('Câu hỏi đã được cập nhật')
                                ->body('Thông tin câu hỏi đã được lưu thành công.')
                                ->send();

                            // Refresh the table
                            $this->dispatch('refreshTable');
                        } catch (\App\Exceptions\Services\QuestionServiceException $e) {
                            Notification::make()
                                ->danger()
                                ->title('Lỗi')
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),

                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Xóa câu hỏi')
                    ->modalDescription('Bạn có chắc chắn muốn xóa câu hỏi này? Hành động này không thể hoàn tác.')
                    ->modalSubmitActionLabel('Xóa')
                    ->modalCancelActionLabel('Hủy')
                    ->action(function (Question $record) { // **FIX:** Changed from using() to action()
                        try {
                            $this->getQuestionService()->deleteById($record->id);
                            // **FIX:** Manually send notification on success
                            Notification::make()
                                ->success()
                                ->title('Thành công')
                                ->body('Câu hỏi đã được xóa khỏi quiz.')
                                ->send();
                        } catch (\App\Exceptions\Services\QuestionServiceException $e) {
                            Notification::make()
                                ->danger()
                                ->title('Lỗi')
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Xóa các câu hỏi đã chọn')
                        ->modalDescription('Bạn có chắc chắn muốn xóa các câu hỏi đã chọn? Hành động này không thể hoàn tác.')
                        ->modalSubmitActionLabel('Xóa tất cả')
                        ->modalCancelActionLabel('Hủy')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $this->getQuestionService()->deleteById($record->id);
                            }
                            Notification::make()
                                ->success()
                                ->title('Thành công')
                                ->body('Các câu hỏi đã được xóa khỏi quiz.')
                                ->send();
                        }),
                ]),
            ])
            ->emptyStateHeading('Chưa có câu hỏi nào')
            ->emptyStateDescription('Hãy thêm câu hỏi đầu tiên cho quiz này.')
            ->emptyStateIcon('heroicon-o-question-mark-circle');
    }
}

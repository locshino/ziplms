<?php

namespace App\Filament\Resources\QuizResource\Pages;

use App\Filament\Resources\QuizResource;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Question;
use App\Models\StudentQuizAnswer;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class TakeQuiz extends Page
{
    protected static string $resource = QuizResource::class;

    protected static string $view = 'filament.resources.quiz-resource.pages.take-quiz';

    protected static ?string $title = 'Làm bài quiz';

    public Quiz $record;
    public ?QuizAttempt $attempt = null;
    public $questions = [];
    public $currentQuestionIndex = 0;
    public $answers = [];
    public $timeRemaining = null;
    public $isCompleted = false;

    public function mount(Quiz $record): void
    {
        $this->record = $record;
        
        // Check if user can take this quiz
        if (!$this->canTakeQuiz()) {
            $this->redirect(route('filament.admin.resources.quizzes.index'));
            return;
        }

        // Get or create quiz attempt
        $this->attempt = QuizAttempt::where('quiz_id', $this->record->id)
            ->where('student_id', Auth::id())
            ->where('completed_at', null)
            ->first();

        if (!$this->attempt) {
            $this->attempt = QuizAttempt::create([
                'quiz_id' => $this->record->id,
                'student_id' => Auth::id(),
                'attempt_number' => $this->getNextAttemptNumber(),
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }

        // Load questions with answer choices
        $this->questions = $this->record->questions()->with('answerChoices')->get()->toArray();
        
        // Load existing answers
        $existingAnswers = StudentQuizAnswer::where('quiz_attempt_id', $this->attempt->id)->get();
        foreach ($existingAnswers as $answer) {
            $this->answers[$answer->question_id] = $answer->answer_choice_id;
        }

        // Calculate time remaining
        if ($this->record->time_limit_minutes) {
            $elapsed = now()->diffInMinutes($this->attempt->started_at);
            $this->timeRemaining = max(0, $this->record->time_limit_minutes - $elapsed);
        }
    }

    protected function canTakeQuiz(): bool
    {
        $user = Auth::user();
        
        // Super admin và admin có thể làm quiz bất kỳ lúc nào
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // Check if quiz is active for students
        if (now() < $this->record->start_at || now() > $this->record->end_at) {
            return false;
        }

        // Check max attempts for students
        if ($this->record->max_attempts) {
            $completedAttempts = QuizAttempt::where('quiz_id', $this->record->id)
                ->where('student_id', Auth::id())
                ->whereNotNull('completed_at')
                ->count();
            
            if ($completedAttempts >= $this->record->max_attempts) {
                return false;
            }
        }

        return true;
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function goToQuestion($index)
    {
        if ($index >= 0 && $index < count($this->questions)) {
            $this->currentQuestionIndex = $index;
        }
    }

    public function saveAnswer($questionId, $answerChoiceId)
    {
        $this->answers[$questionId] = $answerChoiceId;
        
        // Save to database
        StudentQuizAnswer::updateOrCreate(
            [
                'quiz_attempt_id' => $this->attempt->id,
                'question_id' => $questionId
            ],
            ['answer_choice_id' => $answerChoiceId]
        );
    }

    public function submitQuiz()
    {
        // Calculate score
        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($this->questions as $question) {
            $totalPoints += $question['points'];
            
            if (isset($this->answers[$question['id']])) {
                $selectedChoice = collect($question['answer_choices'])
                    ->firstWhere('id', $this->answers[$question['id']]);
                
                if ($selectedChoice && $selectedChoice['is_correct']) {
                    $earnedPoints += $question['points'];
                }
            }
        }

        $score = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;

        // Complete the attempt
        $this->attempt->update([
            'completed_at' => now(),
            'score' => $score,
            'status' => 'completed',
        ]);

        $this->isCompleted = true;
        
        // Show success notification
        Notification::make()
            ->title('Nộp bài thành công')
            ->body('Bạn đã hoàn thành quiz với điểm số: ' . round($score, 1) . '%')
            ->success()
            ->send();
        
        // Redirect to results
        $this->redirect(QuizResource::getUrl('quiz-result', [
            'record' => $this->record->id,
            'attempt' => $this->attempt->id
        ]));
    }

    protected function getActions(): array
    {
        return [
            Action::make('submit')
                ->label('Nộp bài')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Xác nhận nộp bài')
                ->modalDescription('Bạn có chắc chắn muốn nộp bài? Sau khi nộp bài, bạn sẽ không thể thay đổi câu trả lời.')
                ->action('submitQuiz')
                ->visible(!$this->isCompleted),
        ];
    }

    protected function getNextAttemptNumber(): int
    {
        $lastAttempt = QuizAttempt::where('quiz_id', $this->record->id)
            ->where('student_id', Auth::id())
            ->orderBy('attempt_number', 'desc')
            ->first();
            
        return $lastAttempt ? $lastAttempt->attempt_number + 1 : 1;
    }
    
    public function getTimeRemainingFormatted(): ?string
    {
        if (!$this->timeRemaining) {
            return null;
        }
        
        $hours = floor($this->timeRemaining / 60);
        $minutes = $this->timeRemaining % 60;
        
        if ($hours > 0) {
            return sprintf('%d giờ %d phút', $hours, $minutes);
        }
        
        return sprintf('%d phút', $minutes);
    }


}
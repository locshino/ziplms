<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\QuizServiceInterface;
use App\Services\Interfaces\QuestionServiceInterface;
use App\Services\QuizAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuizController extends Controller
{
    protected QuizServiceInterface $quizService;
    protected QuestionServiceInterface $questionService;
    protected QuizAccessService $quizAccessService;

    public function __construct(
        QuizServiceInterface $quizService,
        QuestionServiceInterface $questionService,
        QuizAccessService $quizAccessService
    ) {
        $this->quizService = $quizService;
        $this->questionService = $questionService;
        $this->quizAccessService = $quizAccessService;
    }

    /**
     * Display a listing of available quizzes for student.
     */
    public function index(): View
    {
        $quizzes = $this->quizService->getAvailableQuizzes(Auth::id());
        
        return view('quiz.index', compact('quizzes'));
    }

    /**
     * Show the quiz taking interface.
     */
    public function show(string $id): View
    {
        $quiz = $this->quizService->findById($id);
        $user = Auth::user();
        
        if (!$quiz) {
            abort(404, 'Quiz không tồn tại.');
        }

        // Check if user can take this quiz
        if (!$this->quizAccessService->canTakeQuiz($user, $quiz)) {
            abort(403, 'Bạn không thể làm bài quiz này.');
        }

        // Check for existing incomplete attempt
        $existingAttempt = $this->quizService->continueQuizAttempt($id, Auth::id());
        
        return view('quiz.show', compact('quiz', 'existingAttempt'));
    }

    /**
     * Start a new quiz attempt.
     */
    public function start(string $id)
    {
        try {
            $attempt = $this->quizService->startQuizAttempt($id, Auth::id());
            
            return redirect()->route('quiz.take', $attempt->id)
                ->with('success', 'Bài quiz đã được bắt đầu.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Continue an existing quiz attempt.
     */
    public function continue(string $id)
    {
        $attempt = $this->quizService->continueQuizAttempt($id, Auth::id());
        
        if (!$attempt) {
            return back()->with('error', 'Không tìm thấy bài làm để tiếp tục.');
        }

        return redirect()->route('quiz.take', $attempt->id);
    }

    /**
     * Show quiz taking interface.
     */
    public function take(string $attemptId): View
    {
        $attempt = $this->quizService->getAttemptWithAnswers($attemptId);
        
        if (!$attempt || $attempt->student_id !== Auth::id()) {
            abort(404, 'Bài làm không tồn tại.');
        }

        if ($attempt->status !== 'in_progress') {
            return redirect()->route('quiz.result', $attemptId);
        }

        $quiz = $attempt->quiz;
        $quiz->load(['questions.answerChoices']);
        
        return view('quiz.take', compact('attempt', 'quiz'));
    }

    /**
     * Submit quiz attempt.
     */
    public function submit(Request $request, string $attemptId)
    {
        try {
            $answers = $request->input('answers', []);
            $attempt = $this->quizService->submitQuizAttempt($attemptId, $answers);
            
            return redirect()->route('quiz.result', $attemptId)
                ->with('success', 'Bài quiz đã được nộp thành công.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show quiz result.
     */
    public function result(string $attemptId): View
    {
        $attempt = $this->quizService->getAttemptWithAnswers($attemptId);
        
        if (!$attempt || $attempt->student_id !== Auth::id()) {
            abort(404, 'Bài làm không tồn tại.');
        }

        return view('quiz.result', compact('attempt'));
    }

    /**
     * Show quiz attempt history.
     */
    public function history(string $id): View
    {
        $quiz = $this->quizService->findById($id);
        
        if (!$quiz) {
            abort(404, 'Quiz không tồn tại.');
        }

        $attempts = $this->quizService->getAttemptHistory($id, Auth::id());
        
        return view('quiz.history', compact('quiz', 'attempts'));
    }

    /**
     * Show quiz management interface.
     */
    public function manage(string $id): View
    {
        $quiz = $this->quizService->findById($id);
        
        if (!$quiz) {
            abort(404, 'Quiz không tồn tại.');
        }

        $questions = $this->questionService->getByQuizId($id);
        
        return view('quiz.manage', compact('quiz', 'questions'));
    }
}
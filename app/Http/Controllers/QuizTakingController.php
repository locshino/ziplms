<?php

namespace App\Http\Controllers;

use App\Exceptions\Services\QuizServiceException;
use App\Models\Quiz;
use App\Services\Interfaces\QuizServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class QuizTakingController extends Controller
{
    protected QuizServiceInterface $quizService;

    public function __construct(QuizServiceInterface $quizService)
    {
        $this->middleware('auth');
        $this->quizService = $quizService;
    }

    public function show(Request $request)
    {
        $quizId = $request->get('quiz');

        if (! $quizId) {
            return redirect()->back()->with('error', 'Quiz ID is required.');
        }

        try {
            $user = Auth::user();

            // Get quiz with questions and choices
            $quiz = $this->quizService->getQuizWithQuestionsAndChoices($quizId);

            if (! $quiz) {
                return redirect()->back()->with('error', 'Quiz not found.');
            }

            // Check if quiz is active
            if (! $quiz->is_active) {
                return redirect()->back()->with('error', 'Quiz is not currently available.');
            }

            // Check if student can take this quiz
            if (! $this->quizService->canStudentTakeQuiz($quizId, $user->id)) {
                return redirect()->back()->with('error', 'You are not allowed to take this quiz.');
            }

            // Check for existing incomplete attempt
            $attempt = $this->quizService->continueQuizAttempt($quizId, $user->id);

            // If no incomplete attempt, start a new one
            if (! $attempt) {
                $attempt = $this->quizService->startQuizAttempt($quizId, $user->id);
            }

            // Return the quiz taking view
            return view('quiz.taking', compact('quiz', 'attempt'));

        } catch (QuizServiceException $e) {
            Log::error('QuizServiceException in QuizTakingController::show', [
                'message' => $e->getMessage(),
                'quiz_id' => $quizId,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Exception in QuizTakingController::show', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'quiz_id' => $quizId,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()->with('error', 'Quiz not found or unavailable.');
        }
    }

    public function submit(Request $request)
    {
        $attemptId = $request->get('attempt_id');
        $answers = $request->get('answers', []);

        if (! $attemptId) {
            return redirect()->back()->with('error', 'Attempt ID is required.');
        }

        try {
            $user = Auth::user();

            // Submit quiz attempt using service
            $attempt = $this->quizService->submitQuizAttempt($attemptId, $answers);

            // Calculate score
            $score = $this->quizService->calculateScore($attempt);

            return redirect()->route('filament.app.pages.quiz-results', ['quiz' => $attempt->quiz_id])
                ->with('success', 'Quiz submitted successfully! Your score: '.$score.'%');

        } catch (QuizServiceException $e) {
            Log::error('QuizServiceException in QuizTakingController::submit', [
                'message' => $e->getMessage(),
                'attempt_id' => $attemptId,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Exception in QuizTakingController::submit', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'attempt_id' => $attemptId,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()->with('error', 'Failed to submit quiz.');
        }
    }
}

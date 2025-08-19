<?php

namespace App\Http\Controllers;

use App\Exceptions\Services\QuizServiceException;
use App\Http\Responses\QuizResponse;
use App\Services\Interfaces\QuestionServiceInterface;
use App\Services\Interfaces\QuizServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class QuizController extends Controller
{
    protected QuizServiceInterface $quizService;

    protected QuestionServiceInterface $questionService;

    public function __construct(
        QuizServiceInterface $quizService,
        QuestionServiceInterface $questionService
    ) {
        $this->middleware('auth');
        $this->quizService = $quizService;
        $this->questionService = $questionService;
    }

    /**
     * Display a listing of available quizzes.
     *
     * @return View|JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $quizzes = $this->quizService->getAvailableQuizzesForStudent($user->id);

            if ($request->expectsJson()) {
                return QuizResponse::success($quizzes, 'Quizzes retrieved successfully');
            }

            return view('quiz.index', compact('quizzes'));

        } catch (QuizServiceException $e) {
            Log::warning('Quiz index failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            if ($request->expectsJson()) {
                return QuizResponse::error($e->getMessage(), 400);
            }

            return view('quiz.index', ['quizzes' => collect()])
                ->with('error', $e->getMessage());

        } catch (\Exception $e) {
            Log::error('Error loading quizzes', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson()) {
                return QuizResponse::error('Không thể tải danh sách bài kiểm tra');
            }

            return view('quiz.index', ['quizzes' => collect()])
                ->with('error', 'Không thể tải danh sách bài kiểm tra');
        }
    }

    /**
     * Show a specific quiz.
     *
     * @return View|JsonResponse|RedirectResponse
     */
    public function show(string $id, Request $request)
    {
        try {
            $quiz = $this->quizService->findById($id);

            if (! $quiz) {
                return QuizResponse::quizNotFound($request);
            }

            if (! $quiz->is_published) {
                return QuizResponse::quizNotPublished($request);
            }

            $user = Auth::user();
            if (! $this->quizService->canStudentTakeQuiz($id, $user->id)) {
                return QuizResponse::cannotTakeQuiz($request);
            }

            // Check for existing incomplete attempt
            $existingAttempt = $this->quizService->continueQuizAttempt($id, Auth::id());

            if ($request->expectsJson()) {
                return QuizResponse::success(compact('quiz', 'existingAttempt'), 'Quiz retrieved successfully');
            }

            return view('quiz.show', compact('quiz', 'existingAttempt'));

        } catch (QuizServiceException $e) {
            Log::warning('Quiz show failed', [
                'quiz_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return QuizResponse::errorWithRedirect($request, $e->getMessage(), 400);

        } catch (\Exception $e) {
            Log::error('Error showing quiz', [
                'quiz_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return QuizResponse::errorWithRedirect($request, 'Không thể hiển thị bài kiểm tra');
        }
    }

    /**
     * Start a new quiz attempt.
     *
     * @return JsonResponse|RedirectResponse
     */
    public function start(string $id, Request $request)
    {
        try {
            $user = Auth::user();
            $attempt = $this->quizService->startQuizAttempt($id, $user->id);

            return QuizResponse::quizStarted($request, $attempt, $id);

        } catch (QuizServiceException $e) {
            Log::warning('Quiz start failed', [
                'quiz_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return QuizResponse::errorWithRedirect($request, $e->getMessage(), 400);

        } catch (\Exception $e) {
            Log::error('Error starting quiz', [
                'quiz_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return QuizResponse::errorWithRedirect($request, 'Không thể bắt đầu bài kiểm tra');
        }
    }

    /**
     * Continue an existing quiz attempt.
     *
     * @return JsonResponse|RedirectResponse
     */
    public function continue(string $id, Request $request)
    {
        try {
            $user = Auth::user();
            $attempt = $this->quizService->continueQuizAttempt($id, $user->id);

            if (! $attempt) {
                $message = 'Không có phiên làm bài nào để tiếp tục';

                if ($request->expectsJson()) {
                    return QuizResponse::error($message, 404);
                }

                return redirect()->route('quiz.show', $id)->with('info', $message);
            }

            if ($request->expectsJson()) {
                return QuizResponse::success($attempt, 'Tiếp tục phiên làm bài');
            }

            return redirect()->route('quiz.take', ['id' => $id, 'attempt' => $attempt->id]);

        } catch (QuizServiceException $e) {
            Log::warning('Quiz continue failed', [
                'quiz_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return QuizResponse::errorWithRedirect($request, $e->getMessage(), 400);

        } catch (\Exception $e) {
            Log::error('Error continuing quiz', [
                'quiz_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return QuizResponse::errorWithRedirect($request, 'Không thể tiếp tục bài kiểm tra');
        }
    }

    /**
     * Show the quiz taking interface.
     *
     * @return View|JsonResponse|RedirectResponse
     */
    public function take(string $id, string $attemptId, Request $request)
    {
        try {
            $user = Auth::user();
            $attempt = $this->quizService->getAttemptWithAnswers($attemptId);

            if (! $attempt || $attempt->student_id !== $user->id) {
                return QuizResponse::attemptNotFound($request);
            }

            if ($attempt->status !== 'in_progress') {
                if ($request->expectsJson()) {
                    return QuizResponse::error('Bài kiểm tra đã hoàn thành', 400);
                }

                return redirect()->route('quiz.result', $attemptId);
            }

            $quiz = $attempt->quiz;
            $quiz->load(['questions.answerChoices']);

            if ($request->expectsJson()) {
                return QuizResponse::success(compact('attempt', 'quiz'), 'Quiz attempt loaded');
            }

            return view('quiz.take', compact('attempt', 'quiz'));

        } catch (QuizServiceException $e) {
            Log::warning('Quiz take failed', [
                'quiz_id' => $id,
                'attempt_id' => $attemptId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return QuizResponse::errorWithRedirect($request, $e->getMessage(), 400);

        } catch (\Exception $e) {
            Log::error('Error loading quiz attempt', [
                'quiz_id' => $id,
                'attempt_id' => $attemptId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return QuizResponse::errorWithRedirect($request, 'Không thể tải bài kiểm tra');
        }
    }

    /**
     * Submit quiz answers.
     *
     * @return JsonResponse|RedirectResponse
     */
    public function submit(Request $request, string $id, string $attemptId)
    {
        try {
            $answers = $request->input('answers', []);
            $user = Auth::user();
            $attempt = $this->quizService->submitQuizAttempt($attemptId, $answers);

            return QuizResponse::quizSubmitted($request, $attempt, $id);

        } catch (QuizServiceException $e) {
            Log::warning('Quiz submission failed', [
                'quiz_id' => $id,
                'attempt_id' => $attemptId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return QuizResponse::errorWithRedirect($request, $e->getMessage(), 400);

        } catch (\Exception $e) {
            Log::error('Error submitting quiz', [
                'quiz_id' => $id,
                'attempt_id' => $attemptId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return QuizResponse::errorWithRedirect($request, 'Không thể nộp bài kiểm tra');
        }
    }

    /**
     * Show quiz result.
     *
     * @return View|JsonResponse
     */
    public function result(string $attemptId, Request $request)
    {
        try {
            $user = Auth::user();
            $attempt = $this->quizService->getAttemptWithAnswers($attemptId);

            if (! $attempt || $attempt->student_id !== $user->id) {
                return QuizResponse::attemptNotFound($request);
            }

            if ($request->expectsJson()) {
                return QuizResponse::success($attempt, 'Quiz result retrieved successfully');
            }

            return view('quiz.result', compact('attempt'));

        } catch (QuizServiceException $e) {
            Log::warning('Quiz result failed', [
                'attempt_id' => $attemptId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return QuizResponse::errorWithRedirect($request, $e->getMessage(), 400);

        } catch (\Exception $e) {
            Log::error('Error loading quiz result', [
                'attempt_id' => $attemptId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return QuizResponse::errorWithRedirect($request, 'Không thể tải kết quả bài kiểm tra');
        }
    }

    /**
     * Show quiz attempt history.
     */
    public function history(string $id): View
    {
        $quiz = $this->quizService->findById($id);

        if (! $quiz) {
            abort(404, 'Quiz không tồn tại.');
        }

        $attempts = $this->quizService->getAttemptHistory($id, Auth::id());

        return view('quiz.history', compact('quiz', 'attempts'));
    }

    /**
     * Show quiz management interface for instructors.
     *
     * @return View|JsonResponse|RedirectResponse
     */
    public function manage(string $id, Request $request)
    {
        try {
            $quiz = $this->quizService->findById($id);

            if (! $quiz) {
                return QuizResponse::quizNotFound($request, 'quiz.index');
            }

            // Check if user can manage this quiz (instructor/admin)
            $this->authorize('manage', $quiz);

            $attempts = $this->quizService->getQuizAttempts($id);

            if ($request->expectsJson()) {
                return QuizResponse::success(compact('quiz', 'attempts'), 'Quiz management data retrieved');
            }

            return view('quiz.manage', compact('quiz', 'attempts'));

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Quiz manage authorization failed', [
                'quiz_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return QuizResponse::errorWithRedirect($request, 'Bạn không có quyền quản lý bài kiểm tra này', 403);

        } catch (QuizServiceException $e) {
            Log::warning('Quiz manage failed', [
                'quiz_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return QuizResponse::errorWithRedirect($request, $e->getMessage(), 400);

        } catch (\Exception $e) {
            Log::error('Error loading quiz management', [
                'quiz_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return QuizResponse::errorWithRedirect($request, 'Không thể tải trang quản lý bài kiểm tra');
        }
    }
}

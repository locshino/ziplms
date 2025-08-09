<?php

namespace App\Http\Middleware;

use App\Models\Enrollment;
use App\Models\Quiz;
use App\Services\QuizService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckQuizAccess
{
    public function __construct(
        private QuizService $quizService
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $action = 'view'): Response
    {
        $user = Auth::user();

        if (! $user) {
            abort(401, 'Unauthorized');
        }

        $quizId = $request->route('quiz') ?? $request->route('id');
        $quiz = Quiz::findOrFail($quizId);

        // Check basic quiz permissions based on action
        $permissionMap = [
            'index' => 'view_quizzes',
            'show' => 'view_quizzes',
            'create' => 'create_quizzes',
            'store' => 'create_quizzes',
            'edit' => 'edit_quizzes',
            'update' => 'edit_quizzes',
            'destroy' => 'delete_quizzes',
            'take' => 'take_quizzes',
            'results' => 'view_reports',
        ];

        $requiredPermission = $permissionMap[$action] ?? 'view_quizzes';

        if (! $user->can($requiredPermission)) {
            abort(403, 'You do not have permission to perform this action.');
        }

        // Super admin can access all quizzes
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // Admin can access all quizzes
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Manager can only access quizzes in courses they have permission for
        if ($user->hasRole('manager')) {
            if (! $user->can('manage-course-'.$quiz->course_id)) {
                abort(403, 'You do not have permission to access this course.');
            }

            return $next($request);
        }

        // Teachers can view and grade quizzes but cannot manage them
        if ($user->hasRole('teacher')) {
            if (in_array($action, ['create', 'store', 'edit', 'update', 'destroy'])) {
                abort(403, 'Teachers can only view and grade quizzes.');
            }

            return $next($request);
        }

        // Students can only take quizzes
        if ($user->hasRole('student') && $action === 'take') {
            // Check if student is enrolled in the course
            $isEnrolled = Enrollment::where('student_id', $user->id)
                ->where('course_id', $quiz->course_id)
                ->exists();

            if (! $isEnrolled) {
                abort(403, 'You are not enrolled in this course.');
            }

            // Check if student can take the quiz
            if (! $this->quizService->canTakeQuiz($quiz->id, $user->id)) {
                abort(403, 'You cannot take this quiz at this time.');
            }

            // Add quiz to request for controller use
            $request->merge(['quiz_instance' => $quiz]);

            return $next($request);
        }

        abort(403, 'Access denied.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\UserQuizAssignmentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserQuizAssignmentController extends Controller
{
    protected UserQuizAssignmentServiceInterface $userQuizAssignmentService;

    public function __construct(UserQuizAssignmentServiceInterface $userQuizAssignmentService)
    {
        $this->userQuizAssignmentService = $userQuizAssignmentService;
    }

    /**
     * Lấy tất cả quiz được giao cho user hiện tại
     */
    public function getAssignedQuizzes(Request $request): JsonResponse
    {
        $user = Auth::user();
        $quizzes = $this->userQuizAssignmentService->getAssignedQuizzes($user);

        return response()->json([
            'success' => true,
            'data' => $quizzes->map(function ($quiz) use ($user) {
                return [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'description' => $quiz->description,
                    'course' => [
                        'id' => $quiz->course->id,
                        'name' => $quiz->course->name,
                    ],
                    'start_time' => $quiz->start_time,
                    'end_time' => $quiz->end_time,
                    'duration' => $quiz->duration,
                    'max_attempts' => $quiz->max_attempts,
                    'status' => $this->userQuizAssignmentService->getQuizStatusForUser($user, $quiz),
                    'can_access' => $this->userQuizAssignmentService->canUserAccessQuiz($user, $quiz),
                ];
            }),
        ]);
    }

    /**
     * Lấy quiz được giao theo khóa học
     */
    public function getAssignedQuizzesByCourse(Request $request, int $courseId): JsonResponse
    {
        $user = Auth::user();
        $quizzes = $this->userQuizAssignmentService->getAssignedQuizzesByCourse($user, $courseId);

        return response()->json([
            'success' => true,
            'data' => $quizzes->map(function ($quiz) use ($user) {
                return [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'description' => $quiz->description,
                    'start_time' => $quiz->start_time,
                    'end_time' => $quiz->end_time,
                    'duration' => $quiz->duration,
                    'max_attempts' => $quiz->max_attempts,
                    'status' => $this->userQuizAssignmentService->getQuizStatusForUser($user, $quiz),
                    'can_access' => $this->userQuizAssignmentService->canUserAccessQuiz($user, $quiz),
                ];
            }),
        ]);
    }

    /**
     * Lấy thống kê quiz assignment cho user
     */
    public function getQuizAssignmentStats(Request $request): JsonResponse
    {
        $user = Auth::user();
        $stats = $this->userQuizAssignmentService->getQuizAssignmentStats($user);

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Lấy quiz sắp đến hạn
     */
    public function getUpcomingQuizzes(Request $request): JsonResponse
    {
        $user = Auth::user();
        $days = $request->get('days', 7);
        $quizzes = $this->userQuizAssignmentService->getUpcomingQuizzes($user, $days);

        return response()->json([
            'success' => true,
            'data' => $quizzes->map(function ($quiz) {
                return [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'course_name' => $quiz->course->name,
                    'end_time' => $quiz->end_time,
                    'days_remaining' => $quiz->end_time ? $quiz->end_time->diffInDays(now()) : null,
                ];
            }),
        ]);
    }

    /**
     * Lấy quiz quá hạn
     */
    public function getOverdueQuizzes(Request $request): JsonResponse
    {
        $user = Auth::user();
        $quizzes = $this->userQuizAssignmentService->getOverdueQuizzes($user);

        return response()->json([
            'success' => true,
            'data' => $quizzes->map(function ($quiz) {
                return [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'course_name' => $quiz->course->name,
                    'end_time' => $quiz->end_time,
                    'days_overdue' => $quiz->end_time ? now()->diffInDays($quiz->end_time) : null,
                ];
            }),
        ]);
    }

    /**
     * Kiểm tra quyền truy cập quiz cụ thể
     */
    public function checkQuizAccess(Request $request, int $quizId): JsonResponse
    {
        $user = Auth::user();

        // Tìm quiz
        $quiz = \App\Models\Quiz::find($quizId);

        if (! $quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz không tồn tại',
            ], 404);
        }

        $canAccess = $this->userQuizAssignmentService->canUserAccessQuiz($user, $quiz);
        $status = $this->userQuizAssignmentService->getQuizStatusForUser($user, $quiz);

        return response()->json([
            'success' => true,
            'data' => [
                'quiz_id' => $quiz->id,
                'quiz_title' => $quiz->title,
                'can_access' => $canAccess,
                'status' => $status,
                'message' => $this->getAccessMessage($canAccess, $status),
            ],
        ]);
    }

    /**
     * Lấy thông báo truy cập dựa trên trạng thái
     */
    private function getAccessMessage(bool $canAccess, string $status): string
    {
        if (! $canAccess) {
            return 'Bạn không có quyền truy cập quiz này';
        }

        return match ($status) {
            'upcoming' => 'Quiz chưa mở, vui lòng chờ đến thời gian bắt đầu',
            'available' => 'Quiz có thể làm bài ngay',
            'in_progress' => 'Bạn đang có bài làm dang dở',
            'completed' => 'Bạn đã hoàn thành quiz này',
            'overdue' => 'Quiz đã quá hạn',
            default => 'Trạng thái không xác định'
        };
    }
}

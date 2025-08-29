<?php

namespace App\Services;

use App\Enums\Status\QuizAttemptStatus;
use App\Enums\Status\QuizStatus;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Services\Interfaces\QuizFilterServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Service xử lý logic lọc dữ liệu quiz
 * Cung cấp các phương thức lọc quiz theo trạng thái khác nhau
 */
class QuizFilterService implements QuizFilterServiceInterface
{
    /**
     * Lấy tất cả quiz của user với thông tin chi tiết
     */
    public function getAllQuizzes(?User $user = null): Collection
    {
        $user = $user ?? Auth::user();
        $userId = $user->id;

        return Quiz::with([
            'courses' => function ($query) use ($userId) {
                $query->whereHas('users', function ($q) use ($userId) {
                    $q->where('users.id', $userId);
                })->withPivot('start_at', 'end_at');
            },
            'questions',
            'attempts' => function ($query) use ($userId) {
                $query->where('student_id', $userId)
                    ->orderBy('created_at', 'desc');
            }
        ])
        ->whereHas('courses.users', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        })
        ->where('status', QuizStatus::PUBLISHED)
        ->get();
    }

    /**
     * Lọc quiz chưa nộp (chưa có attempt hoặc có attempt in_progress)
     */
    public function getUnsubmittedQuizzes(?User $user = null): Collection
    {
        $allQuizzes = $this->getAllQuizzes($user);
        $user = $user ?? Auth::user();
        $userId = $user->id;

        return $allQuizzes->filter(function ($quiz) use ($userId) {
            // Lọc attempts của user hiện tại
            $userAttempts = $quiz->attempts->where('student_id', $userId);
            
            // Nếu không có attempt nào của user này
            if ($userAttempts->isEmpty()) {
                return $this->canTakeQuiz($quiz, $userId);
            }

            // Nếu có attempt in_progress của user này
            $inProgressAttempt = $userAttempts->where('status', QuizAttemptStatus::IN_PROGRESS)->first();
            if ($inProgressAttempt) {
                return true;
            }

            // Nếu có attempts đã completed/graded, quiz này không còn là "chưa nộp"
            $completedAttempts = $userAttempts->whereIn('status', [
                QuizAttemptStatus::COMPLETED,
                QuizAttemptStatus::GRADED
            ]);
            
            if ($completedAttempts->count() > 0) {
                return false; // Quiz đã được nộp ít nhất 1 lần
            }

            return false;
        });
    }

    /**
     * Lọc quiz quá hạn (đã hết thời gian làm bài)
     */
    public function getOverdueQuizzes(?User $user = null): Collection
    {
        $allQuizzes = $this->getAllQuizzes($user);
        $user = $user ?? Auth::user();
        $userId = $user->id;
        $now = now();

        return $allQuizzes->filter(function ($quiz) use ($userId, $now) {
            // Kiểm tra thời gian kết thúc của quiz trong course
            $isOverdue = false;
            
            // Duyệt qua tất cả courses mà user đã tham gia và có quiz này
            foreach ($quiz->courses as $course) {
                // Lấy thông tin pivot của course_quiz (bảng course_quiz)
                $courseQuizPivot = $course->pivot;
                $endAt = $courseQuizPivot->end_at;
                
                // Nếu có thời gian kết thúc và đã quá hạn
                if ($endAt && $now->gt($endAt)) {
                    $isOverdue = true;
                    break;
                }
            }

            // Nếu quiz quá hạn, kiểm tra xem user đã hoàn thành chưa
            if ($isOverdue) {
                $completedAttempts = $quiz->attempts->where('student_id', $userId)
                    ->whereIn('status', [
                        QuizAttemptStatus::COMPLETED,
                        QuizAttemptStatus::GRADED
                    ]);
                
                // Trả về true nếu chưa có attempt hoàn thành nào
                return $completedAttempts->isEmpty();
            }

            return false;
        });
    }

    /**
     * Lọc quiz đã nộp (có ít nhất 1 attempt completed/graded)
     */
    public function getSubmittedQuizzes(?User $user = null): Collection
    {
        $allQuizzes = $this->getAllQuizzes($user);
        $user = $user ?? Auth::user();
        $userId = $user->id;

        return $allQuizzes->filter(function ($quiz) use ($userId) {
            $completedAttempts = $quiz->attempts->where('student_id', $userId)
                ->whereIn('status', [
                    QuizAttemptStatus::COMPLETED,
                    QuizAttemptStatus::GRADED
                ]);
            
            return $completedAttempts->count() > 0;
        });
    }

    /**
     * Lấy thống kê quiz theo từng loại
     */
    public function getQuizStatistics(?User $user = null): array
    {
        $user = $user ?? Auth::user();
        
        $allQuizzes = $this->getAllQuizzes($user);
        $unsubmittedQuizzes = $this->getUnsubmittedQuizzes($user);
        $overdueQuizzes = $this->getOverdueQuizzes($user);
        $submittedQuizzes = $this->getSubmittedQuizzes($user);
        $retakeableQuizzes = $this->getRetakeableQuizzes($user);
        
        return [
            'total' => $allQuizzes->count(),
            'unsubmitted' => $unsubmittedQuizzes->count(),
            'overdue' => $overdueQuizzes->count(),
            'submitted' => $submittedQuizzes->count(),
            'retakeable' => $retakeableQuizzes->count(),
        ];
    }

    /**
     * Lọc quiz có thể làm lại (đã nộp nhưng còn lượt làm)
     */
    public function getRetakeableQuizzes(?User $user = null): Collection
    {
        $allQuizzes = $this->getAllQuizzes($user);
        $user = $user ?? Auth::user();
        $userId = $user->id;

        return $allQuizzes->filter(function ($quiz) use ($userId) {
            // Lọc attempts của user hiện tại
            $userAttempts = $quiz->attempts->where('student_id', $userId);
            
            // Phải có ít nhất 1 attempt completed/graded
            $completedAttempts = $userAttempts->whereIn('status', [
                QuizAttemptStatus::COMPLETED,
                QuizAttemptStatus::GRADED
            ]);
            
            if ($completedAttempts->count() === 0) {
                return false;
            }
            
            // Và còn lượt làm
            return $this->hasRemainingAttempts($quiz, $userId);
        });
    }

    /**
     * Lọc quiz theo loại
     */
    public function getQuizzesByType(string $type, ?User $user = null): Collection
    {
        return match ($type) {
            'all' => $this->getAllQuizzes($user),
            'unsubmitted' => $this->getUnsubmittedQuizzes($user),
            'overdue' => $this->getOverdueQuizzes($user),
            'submitted' => $this->getSubmittedQuizzes($user),
            'retakeable' => $this->getRetakeableQuizzes($user),
            default => collect(),
        };
    }

    /**
     * Kiểm tra user có thể làm quiz không
     */
    private function canTakeQuiz(Quiz $quiz, string $userId): bool
    {
        $now = now();

        // Kiểm tra thời gian trong course_quizzes
        $userCourses = $quiz->courses()->whereHas('users', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        })->get();

        $canTakeInAnyCourse = false;
        foreach ($userCourses as $course) {
            $courseQuiz = $course->pivot;
            $startAt = $courseQuiz->start_at;
            $endAt = $courseQuiz->end_at;

            // Nếu không có giới hạn thời gian hoặc trong khoảng thời gian hợp lệ
            if ((!$startAt || $now->gte($startAt)) && (!$endAt || $now->lte($endAt))) {
                $canTakeInAnyCourse = true;
                break;
            }
        }

        if (!$canTakeInAnyCourse) {
            return false;
        }

        // Kiểm tra số lần thử
        return $this->hasRemainingAttempts($quiz, $userId);
    }

    /**
     * Kiểm tra còn lượt làm bài không
     */
    private function hasRemainingAttempts(Quiz $quiz, string $userId): bool
    {
        $maxAttempts = $quiz->max_attempts;
        
        // Nếu max_attempts = 0 hoặc null, cho phép làm không giới hạn
        if ($maxAttempts === 0 || $maxAttempts === null) {
            return true;
        }
        
        $usedAttempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $userId)
            ->whereIn('status', [QuizAttemptStatus::COMPLETED, QuizAttemptStatus::GRADED])
            ->count();
            
        return $usedAttempts < $maxAttempts;
    }

    /**
     * Lấy trạng thái chi tiết của quiz cho user
     */
    public function getQuizDetailedStatus(Quiz $quiz, ?User $user = null): array
    {
        $user = $user ?? Auth::user();
        $userId = $user->id;
        $now = now();

        // Lọc attempts của user hiện tại
        $userAttempts = $quiz->attempts->where('student_id', $userId);
        $inProgressAttempt = $userAttempts->where('status', QuizAttemptStatus::IN_PROGRESS)->first();
        $completedAttempts = $userAttempts->whereIn('status', [
            QuizAttemptStatus::COMPLETED,
            QuizAttemptStatus::GRADED
        ]);
        $latestCompletedAttempt = $completedAttempts->sortByDesc('created_at')->first();

        // Kiểm tra thời gian
        $userCourses = $quiz->courses()->whereHas('users', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        })->get();

        $isOverdue = false;
        $hasValidTiming = false;
        
        foreach ($userCourses as $course) {
            $courseQuiz = $course->pivot;
            $startAt = $courseQuiz->start_at;
            $endAt = $courseQuiz->end_at;

            if ($endAt && $now->gt($endAt)) {
                $isOverdue = true;
            }
            
            if ((!$startAt || $now->gte($startAt)) && (!$endAt || $now->lte($endAt))) {
                $hasValidTiming = true;
            }
        }

        $canTake = $this->canTakeQuiz($quiz, $userId);
        $hasRemainingAttempts = $this->hasRemainingAttempts($quiz, $userId);

        return [
            'quiz_id' => $quiz->id,
            'title' => $quiz->title,
            'can_take' => $canTake,
            'is_overdue' => $isOverdue,
            'has_valid_timing' => $hasValidTiming,
            'has_remaining_attempts' => $hasRemainingAttempts,
            'in_progress_attempt' => $inProgressAttempt,
            'completed_attempts_count' => $completedAttempts->count(),
            'latest_completed_attempt' => $latestCompletedAttempt,
            'total_attempts' => $userAttempts->count(),
            'max_attempts' => $quiz->max_attempts,
        ];
    }

    /**
     * Lọc quiz theo khóa học
     */
    public function filterByCourse($quizzes, ?string $courseId = null)
    {
        if (!$courseId) {
            return $quizzes;
        }

        return $quizzes->filter(function ($quiz) use ($courseId) {
            return $quiz->courses->contains('id', $courseId);
        });
    }

    /**
     * Lọc quiz theo từ khóa tìm kiếm
     */
    public function filterBySearch($quizzes, ?string $searchTerm = null)
    {
        if (!$searchTerm) {
            return $quizzes;
        }

        $searchTerm = strtolower(trim($searchTerm));
        
        return $quizzes->filter(function ($quiz) use ($searchTerm) {
            return str_contains(strtolower($quiz->title), $searchTerm) ||
                   str_contains(strtolower($quiz->description ?? ''), $searchTerm) ||
                   $quiz->courses->some(function ($course) use ($searchTerm) {
                       return str_contains(strtolower($course->title), $searchTerm);
                   });
        });
    }

    /**
     * Áp dụng tất cả các bộ lọc
     */
    public function applyAllFilters($quizzes, ?string $courseId = null, ?string $searchTerm = null)
    {
        $filtered = $quizzes;
        
        // Lọc theo khóa học
        $filtered = $this->filterByCourse($filtered, $courseId);
        
        // Lọc theo từ khóa tìm kiếm
        $filtered = $this->filterBySearch($filtered, $searchTerm);
        
        return $filtered;
    }

    /**
     * Lọc quiz đang làm (có attempt in_progress)
     */
    public function getInProgressQuizzes(?User $user = null): Collection
    {
        $allQuizzes = $this->getAllQuizzes($user);
        $user = $user ?? Auth::user();
        $userId = $user->id;

        return $allQuizzes->filter(function ($quiz) use ($userId) {
            $inProgressAttempt = $quiz->attempts->where('student_id', $userId)
                ->where('status', QuizAttemptStatus::IN_PROGRESS)
                ->first();
            return $inProgressAttempt !== null;
        });
    }

    /**
     * Lọc quiz theo trạng thái
     */
    public function getQuizzesByStatus(string $status, ?User $user = null): Collection
    {
        return match ($status) {
            'unsubmitted' => $this->getUnsubmittedQuizzes($user),
            'submitted' => $this->getSubmittedQuizzes($user),
            'in_progress' => $this->getInProgressQuizzes($user),
            'overdue' => $this->getOverdueQuizzes($user),
            'retakeable' => $this->getRetakeableQuizzes($user),
            default => $this->getAllQuizzes($user),
        };
    }

    /**
     * Lọc quiz theo khóa học
     */
    public function getQuizzesByCourse(string $courseId, ?User $user = null): Collection
    {
        $allQuizzes = $this->getAllQuizzes($user);
        return $allQuizzes->filter(function ($quiz) use ($courseId) {
            return $quiz->courses->contains('id', $courseId);
        });
    }

    /**
     * Lọc quiz theo thời gian
     */
    public function getQuizzesByDateRange(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate, ?User $user = null): Collection
    {
        $allQuizzes = $this->getAllQuizzes($user);
        return $allQuizzes->filter(function ($quiz) use ($startDate, $endDate) {
            foreach ($quiz->courses as $course) {
                $courseQuiz = $course->pivot;
                $quizStartAt = $courseQuiz->start_at;
                $quizEndAt = $courseQuiz->end_at;
                
                if ($quizStartAt && $quizEndAt) {
                    if ($quizStartAt >= $startDate && $quizEndAt <= $endDate) {
                        return true;
                    }
                }
            }
            return false;
        });
    }

    /**
     * Lọc quiz sắp hết hạn
     */
    public function getUpcomingDeadlineQuizzes(?User $user = null, int $days = 7): Collection
    {
        $allQuizzes = $this->getAllQuizzes($user);
        $now = now();
        $deadline = $now->copy()->addDays($days);

        return $allQuizzes->filter(function ($quiz) use ($now, $deadline) {
            foreach ($quiz->courses as $course) {
                $courseQuiz = $course->pivot;
                $endAt = $courseQuiz->end_at;
                
                if ($endAt && $endAt > $now && $endAt <= $deadline) {
                    return true;
                }
            }
            return false;
        });
    }

    /**
     * Lọc quiz theo điểm số
     */
    public function getQuizzesByScoreRange(float $minScore, float $maxScore, ?User $user = null): Collection
    {
        $allQuizzes = $this->getAllQuizzes($user);
        $user = $user ?? Auth::user();
        $userId = $user->id;

        return $allQuizzes->filter(function ($quiz) use ($userId, $minScore, $maxScore) {
            $bestAttempt = $quiz->attempts->where('student_id', $userId)
                ->whereIn('status', [QuizAttemptStatus::COMPLETED, QuizAttemptStatus::GRADED])
                ->sortByDesc('points')
                ->first();
            
            if (!$bestAttempt) {
                return false;
            }
            
            return $bestAttempt->points >= $minScore && $bestAttempt->points <= $maxScore;
        });
    }

    /**
     * Lọc quiz theo số lần thử
     */
    public function getQuizzesByAttemptCount(int $attemptCount, ?User $user = null): Collection
    {
        $allQuizzes = $this->getAllQuizzes($user);
        $user = $user ?? Auth::user();
        $userId = $user->id;

        return $allQuizzes->filter(function ($quiz) use ($userId, $attemptCount) {
            $userAttemptCount = $quiz->attempts->where('student_id', $userId)->count();
            return $userAttemptCount == $attemptCount;
        });
    }

    /**
     * Tìm kiếm quiz theo từ khóa
     */
    public function searchQuizzes(string $keyword, ?User $user = null): Collection
    {
        $allQuizzes = $this->getAllQuizzes($user);
        $keyword = strtolower(trim($keyword));
        
        return $allQuizzes->filter(function ($quiz) use ($keyword) {
            return str_contains(strtolower($quiz->title), $keyword) ||
                   str_contains(strtolower($quiz->description ?? ''), $keyword) ||
                   $quiz->courses->some(function ($course) use ($keyword) {
                       return str_contains(strtolower($course->title), $keyword);
                   });
        });
    }

    /**
     * Lọc quiz theo độ khó
     */
    public function getQuizzesByDifficulty(string $difficulty, ?User $user = null): Collection
    {
        $allQuizzes = $this->getAllQuizzes($user);
        return $allQuizzes->filter(function ($quiz) use ($difficulty) {
            return strtolower($quiz->difficulty ?? '') === strtolower($difficulty);
        });
    }

    /**
     * Lọc quiz theo thời gian làm bài
     */
    public function getQuizzesByDuration(int $minDuration, int $maxDuration, ?User $user = null): Collection
    {
        $allQuizzes = $this->getAllQuizzes($user);
        return $allQuizzes->filter(function ($quiz) use ($minDuration, $maxDuration) {
            $duration = $quiz->time_limit ?? 0;
            return $duration >= $minDuration && $duration <= $maxDuration;
        });
    }
}
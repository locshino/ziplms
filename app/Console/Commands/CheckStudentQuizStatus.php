<?php

namespace App\Console\Commands;

use App\Enums\Status\QuizStatus;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class CheckStudentQuizStatus extends Command
{
    protected $signature = 'quiz:check-student {email=student@example.com}';
    protected $description = 'Kiểm tra trạng thái quiz của học sinh';

    public function handle()
    {
        $email = $this->argument('email');
        
        // Tìm user theo email
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("Không tìm thấy user với email: {$email}");
            return 1;
        }
        
        $this->info("=== KIỂM TRA QUIZ CHO USER: {$user->name} ({$email}) ===");
        $this->newLine();
        
        // Lấy các khóa học mà user đã đăng ký
        $userCourses = $user->courses()->get();
        
        if ($userCourses->isEmpty()) {
            $this->warn('User chưa đăng ký khóa học nào.');
            return 0;
        }
        
        $this->info('Khóa học đã đăng ký:');
        foreach ($userCourses as $course) {
            $this->line("- {$course->title} (ID: {$course->id})");
        }
        $this->newLine();
        
        // Lấy tất cả quiz từ các khóa học đã đăng ký
        $allQuizzes = collect();
        foreach ($userCourses as $course) {
            $courseQuizzes = $course->quizzes()->get();
            foreach ($courseQuizzes as $quiz) {
                $quiz->course_info = $course;
                $quiz->course_quiz_info = $course->quizzes()->where('quiz_id', $quiz->id)->first();
                $allQuizzes->push($quiz);
            }
        }
        
        if ($allQuizzes->isEmpty()) {
            $this->warn('Không có quiz nào trong các khóa học đã đăng ký.');
            return 0;
        }
        
        $this->info("Tổng số quiz: {$allQuizzes->count()}");
        $this->newLine();
        
        $availableQuizzes = 0;
        $lockedQuizzes = 0;
        
        foreach ($allQuizzes as $quiz) {
            $canTake = $this->checkCanTakeQuiz($quiz, $user);
            $status = $this->getQuizStatusForUser($quiz, $user);
            
            if ($canTake) {
                $availableQuizzes++;
                $this->info("✅ QUIZ CÓ THỂ LÀM: {$quiz->title}");
            } else {
                $lockedQuizzes++;
                $this->error("❌ QUIZ BỊ KHÓA: {$quiz->title}");
            }
            
            $this->line("   📚 Khóa học: {$quiz->course_info->title}");
            $this->line("   📊 Trạng thái quiz: {$quiz->status->value}");
            $this->line("   🎯 Số lần làm tối đa: " . ($quiz->max_attempts ?: 'Không giới hạn'));
            
            // Kiểm tra số lần đã làm
            $attempts = QuizAttempt::where('quiz_id', $quiz->id)
                ->where('student_id', $user->id)
                ->count();
            $this->line("   📝 Đã làm: {$attempts} lần");
            
            // Kiểm tra thời gian
            $courseQuiz = $quiz->course_quiz_info;
            if ($courseQuiz) {
                $startAt = $courseQuiz->start_at;
                $endAt = $courseQuiz->end_at;
                $now = now();
                
                if ($startAt) {
                    $this->line("   ⏰ Thời gian bắt đầu: {$startAt->format('d/m/Y H:i:s')}");
                    if ($now->lt($startAt)) {
                        $this->line("   ⚠️  Chưa đến thời gian làm bài");
                    }
                }
                
                if ($endAt) {
                    $this->line("   ⏰ Thời gian kết thúc: {$endAt->format('d/m/Y H:i:s')}");
                    if ($now->gt($endAt)) {
                        $this->line("   ⚠️  Đã hết thời gian làm bài");
                    }
                }
            }
            
            // Lý do bị khóa
            if (!$canTake) {
                $reasons = $this->getBlockReasons($quiz, $user);
                $this->line("   🚫 Lý do bị khóa:");
                foreach ($reasons as $reason) {
                    $this->line("      - {$reason}");
                }
            }
            
            $this->newLine();
        }
        
        // Tổng kết
        $this->info("=== TỔNG KẾT ===");
        $this->info("📊 Tổng số quiz: {$allQuizzes->count()}");
        $this->info("✅ Quiz có thể làm: {$availableQuizzes}");
        $this->error("❌ Quiz bị khóa: {$lockedQuizzes}");
        
        return 0;
    }
    
    private function checkCanTakeQuiz($quiz, $user)
    {
        $now = now();
        
        // Kiểm tra status published
        if ($quiz->status !== QuizStatus::PUBLISHED) {
            return false;
        }
        
        // Kiểm tra thời gian từ course_quiz
        $courseQuiz = $quiz->course_quiz_info;
        if ($courseQuiz) {
            $startAt = $courseQuiz->start_at;
            $endAt = $courseQuiz->end_at;
            
            if ($startAt && $now->lt($startAt)) {
                return false;
            }
            
            if ($endAt && $now->gt($endAt)) {
                return false;
            }
        }
        
        // Kiểm tra số lần làm bài
        $maxAttempts = $quiz->max_attempts;
        $attempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $user->id)
            ->count();
            
        if ($maxAttempts !== 0 && $maxAttempts !== null && $attempts >= $maxAttempts) {
            return false;
        }
        
        return true;
    }
    
    private function getBlockReasons($quiz, $user)
    {
        $reasons = [];
        $now = now();
        
        // Kiểm tra status
        if ($quiz->status !== QuizStatus::PUBLISHED) {
            $reasons[] = "Quiz chưa được xuất bản (Status: {$quiz->status->value})";
        }
        
        // Kiểm tra thời gian
        $courseQuiz = $quiz->course_quiz_info;
        if ($courseQuiz) {
            $startAt = $courseQuiz->start_at;
            $endAt = $courseQuiz->end_at;
            
            if ($startAt && $now->lt($startAt)) {
                $reasons[] = "Chưa đến thời gian làm bài (Bắt đầu: {$startAt->format('d/m/Y H:i:s')})";
            }
            
            if ($endAt && $now->gt($endAt)) {
                $reasons[] = "Đã hết thời gian làm bài (Kết thúc: {$endAt->format('d/m/Y H:i:s')})";
            }
        }
        
        // Kiểm tra số lần làm bài
        $maxAttempts = $quiz->max_attempts;
        $attempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $user->id)
            ->count();
            
        if ($maxAttempts !== 0 && $maxAttempts !== null && $attempts >= $maxAttempts) {
            $reasons[] = "Đã hết số lần làm bài ({$attempts}/{$maxAttempts})";
        }
        
        return $reasons;
    }
    
    private function getQuizStatusForUser($quiz, $user)
    {
        // Logic tương tự như trong MyQuiz.php
        $canTake = $this->checkCanTakeQuiz($quiz, $user);
        
        if ($canTake) {
            return 'available';
        }
        
        return 'locked';
    }
}
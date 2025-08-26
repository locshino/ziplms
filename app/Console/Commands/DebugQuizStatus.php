<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Enums\Status\QuizStatus;
use App\Enums\Status\QuizAttemptStatus;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DebugQuizStatus extends Command
{
    protected $signature = 'quiz:debug-status {email=student@example.com}';
    protected $description = 'Debug quiz status for a specific user';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }

        $this->info("Debugging quiz status for: {$user->name} ({$user->email})");
        $this->line(str_repeat('=', 80));

        // Get quizzes using the same logic as MyQuiz.php
        $userId = $user->id;
        $quizzesQuery = Quiz::with([
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
            });

        $quizzes = $quizzesQuery->get();
        
        $this->info("Found {$quizzes->count()} quizzes for this user.");
        $this->line('');

        foreach ($quizzes as $quiz) {
            $this->line("Quiz: {$quiz->title} (ID: {$quiz->id})");
            $this->line("Status: {$quiz->status->value} (" . $quiz->status->getLabel() . ")");
            $this->line("Max Attempts: {$quiz->max_attempts}");
            $this->line("Time Limit: {$quiz->time_limit_minutes} minutes");
            $this->line("Is Active: " . ($quiz->isActive ? 'Yes' : 'No'));
            
            // Check canTakeQuiz logic step by step
            $this->line("\n--- canTakeQuiz Debug ---");
            
            // Step 1: Check if quiz is published
            $isPublished = $quiz->status === QuizStatus::PUBLISHED;
            $this->line("1. Is Published: " . ($isPublished ? 'Yes' : 'No') . " (status: {$quiz->status->value})");
            
            if (!$isPublished) {
                $this->error("   ❌ Quiz not published - canTakeQuiz = false");
                $this->line('');
                continue;
            }
            
            // Step 2: Check course enrollment and timing
            $userCourses = $quiz->courses()->whereHas('users', function ($q) use ($userId) {
                $q->where('users.id', $userId);
            })->get();
            
            $this->line("2. User Courses: {$userCourses->count()}");
            
            $hasValidTiming = false;
            $now = now();
            
            foreach ($userCourses as $course) {
                $courseQuiz = $course->pivot;
                $startAt = $courseQuiz->start_at;
                $endAt = $courseQuiz->end_at;
                
                $this->line("   Course: {$course->title}");
                $this->line("   Start: " . ($startAt ? $startAt->format('Y-m-d H:i:s') : 'null'));
                $this->line("   End: " . ($endAt ? $endAt->format('Y-m-d H:i:s') : 'null'));
                $this->line("   Now: {$now->format('Y-m-d H:i:s')}");
                
                $startValid = !$startAt || $now->gte($startAt);
                $endValid = !$endAt || $now->lte($endAt);
                $courseValid = $startValid && $endValid;
                
                $this->line("   Start Valid: " . ($startValid ? 'Yes' : 'No'));
                $this->line("   End Valid: " . ($endValid ? 'Yes' : 'No'));
                $this->line("   Course Valid: " . ($courseValid ? 'Yes' : 'No'));
                
                if ($courseValid) {
                    $hasValidTiming = true;
                }
            }
            
            $this->line("   Has Valid Timing: " . ($hasValidTiming ? 'Yes' : 'No'));
            
            if (!$hasValidTiming) {
                $this->error("   ❌ No valid timing - canTakeQuiz = false");
                $this->line('');
                continue;
            }
            
            // Step 3: Check attempts
            $attempts = QuizAttempt::where('quiz_id', $quiz->id)
                ->where('student_id', $userId)
                ->whereIn('status', [QuizAttemptStatus::COMPLETED->value, QuizAttemptStatus::GRADED->value])
                ->count();
            
            $maxAttempts = $quiz->max_attempts;
            if ($maxAttempts === null || $maxAttempts === 0) {
                $maxAttempts = null; // Unlimited
            }
            
            $this->line("3. Attempts: {$attempts}");
            $this->line("   Max Attempts: " . ($maxAttempts === null ? 'Unlimited' : $maxAttempts));
            
            $hasRemainingAttempts = $maxAttempts === null || $attempts < $maxAttempts;
            $this->line("   Has Remaining Attempts: " . ($hasRemainingAttempts ? 'Yes' : 'No'));
            
            if (!$hasRemainingAttempts) {
                $this->error("   ❌ No remaining attempts - canTakeQuiz = false");
                $this->line('');
                continue;
            }
            
            $this->info("   ✅ canTakeQuiz = true");
            
            // Now check getQuizStatus logic
            $this->line("\n--- getQuizStatus Debug ---");
            
            $allAttempts = QuizAttempt::where('quiz_id', $quiz->id)
                ->where('student_id', $userId)
                ->get();
            
            $inProgressAttempt = $allAttempts->where('status', QuizAttemptStatus::IN_PROGRESS->value)->first();
            $completedAttempts = $allAttempts->whereIn('status', [QuizAttemptStatus::COMPLETED->value, QuizAttemptStatus::GRADED->value]);
            $latestCompletedAttempt = $completedAttempts->sortByDesc('created_at')->first();
            
            $this->line("All Attempts: {$allAttempts->count()}");
            $this->line("In Progress: " . ($inProgressAttempt ? 'Yes' : 'No'));
            $this->line("Completed Attempts: {$completedAttempts->count()}");
            
            if (!$quiz->isActive) {
                $this->line("Status: inactive (Quiz not active)");
            } elseif ($inProgressAttempt) {
                $this->line("Status: in_progress");
            } elseif ($maxAttempts !== null && $attempts >= $maxAttempts) {
                $this->line("Status: max_attempts_reached");
            } elseif (!$hasValidTiming && $allAttempts->isEmpty()) {
                if (!$isPublished) {
                    $this->line("Status: not_published");
                } elseif (!$hasValidTiming) {
                    $this->line("Status: time_restricted");
                } else {
                    $this->line("Status: no_access");
                }
            } elseif ($latestCompletedAttempt && $hasRemainingAttempts) {
                $this->line("Status: completed (can retake)");
            } else {
                $this->line("Status: available");
            }
            
            $this->line('');
            $this->line(str_repeat('-', 60));
            $this->line('');
        }
        
        return 0;
    }
}
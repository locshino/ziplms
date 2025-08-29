<?php

namespace App\Services\Interfaces;

use App\Models\Quiz;
use App\Models\User;

interface QuizAccessServiceInterface
{
    /**
     * Check if user can view quiz
     */
    public function canViewQuiz(User $user, Quiz $quiz): bool;

    /**
     * Check if user can take quiz
     */
    public function canTakeQuiz(User $user, Quiz $quiz): bool;

    /**
     * Check if user can edit quiz
     */
    public function canEditQuiz(User $user, Quiz $quiz): bool;

    /**
     * Check if user can delete quiz
     */
    public function canDeleteQuiz(User $user, Quiz $quiz): bool;

    /**
     * Check if user can manage quiz
     */
    public function canManageQuiz(User $user, Quiz $quiz): bool;

    /**
     * Check if user is enrolled in quiz courses
     */
    public function isEnrolledInQuizCourses(User $user, Quiz $quiz): bool;

    /**
     * Check if user can view quiz results
     */
    public function canViewQuizResults(User $user, Quiz $quiz): bool;

    /**
     * Check if user can grade quiz
     */
    public function canGradeQuiz(User $user, Quiz $quiz): bool;

    /**
     * Get accessible quizzes for user
     */
    public function getAccessibleQuizzes(User $user): \Illuminate\Database\Eloquent\Collection;

    /**
     * Check if quiz is accessible at current time
     */
    public function isQuizAccessibleNow(Quiz $quiz): bool;

    /**
     * Check if user has permission for specific quiz action
     */
    public function hasQuizPermission(User $user, Quiz $quiz, string $permission): bool;
}
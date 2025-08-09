<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Libs\Roles\RoleHelper;
use App\Libs\Permissions\PermissionHelper;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuizPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_quiz');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Quiz $quiz): bool
    {
        // Super admin and admin can view all quizzes
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return true;
        }
        
        // Teachers can view quizzes in courses they teach
        if (RoleHelper::isTeacher($user) && $quiz->course && $quiz->course->teachers()->where('user_id', $user->id)->exists()) {
            return true;
        }
        
        // Students can view quizzes in courses they are enrolled in
        if (RoleHelper::isStudent($user) && $quiz->course && $quiz->course->enrollments()->where('user_id', $user->id)->exists()) {
            return true;
        }
        
        return $user->can('view_quiz');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_quiz');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Quiz $quiz): bool
    {
        // Super admin and admin can update all quizzes
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can('update_quiz');
        }
        
        // Teachers can update quizzes in courses they teach
        if (RoleHelper::isTeacher($user) && $quiz->course && $quiz->course->teachers()->where('user_id', $user->id)->exists()) {
            return $user->can('update_quiz');
        }
        
        return $user->can('update_quiz');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Quiz $quiz): bool
    {
        // Super admin and admin can delete all quizzes
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can('delete_quiz');
        }
        
        // Teachers can delete quizzes in courses they teach
        if (RoleHelper::isTeacher($user) && $quiz->course && $quiz->course->teachers()->where('user_id', $user->id)->exists()) {
            return $user->can('delete_quiz');
        }
        
        return false;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_quiz');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Quiz $quiz): bool
    {
        return $user->can('force_delete_quiz');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_quiz');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Quiz $quiz): bool
    {
        return $user->can('restore_quiz');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_quiz');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Quiz $quiz): bool
    {
        return $user->can('replicate_quiz');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_quiz');
    }

    /**
     * Determine whether the user can take the quiz.
     */
    public function take(User $user, Quiz $quiz): bool
    {
        // Check if user is a student
        if (! $user->hasRole('student')) {
            return false;
        }

        // Check if user is enrolled in the course
        return $user->enrollments()->where('course_id', $quiz->course_id)->exists();
    }
}

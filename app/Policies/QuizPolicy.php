<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Quiz;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuizPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_quizzes::quiz');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Quiz $quiz): bool
    {
        return $user->can('view_quizzes::quiz');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_quizzes::quiz');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Quiz $quiz): bool
    {
        return $user->can('update_quizzes::quiz');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Quiz $quiz): bool
    {
        return $user->can('delete_quizzes::quiz');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_quizzes::quiz');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Quiz $quiz): bool
    {
        return $user->can('force_delete_quizzes::quiz');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_quizzes::quiz');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Quiz $quiz): bool
    {
        return $user->can('restore_quizzes::quiz');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_quizzes::quiz');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Quiz $quiz): bool
    {
        return $user->can('replicate_quizzes::quiz');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_quizzes::quiz');
    }
}

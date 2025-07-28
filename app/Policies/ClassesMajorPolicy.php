<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ClassesMajor;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassesMajorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_class::major');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClassesMajor $classesMajor): bool
    {
        return $user->can('view_class::major');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_class::major');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClassesMajor $classesMajor): bool
    {
        return $user->can('update_class::major');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClassesMajor $classesMajor): bool
    {
        return $user->can('delete_class::major');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_class::major');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, ClassesMajor $classesMajor): bool
    {
        return $user->can('force_delete_class::major');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_class::major');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, ClassesMajor $classesMajor): bool
    {
        return $user->can('restore_class::major');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_class::major');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, ClassesMajor $classesMajor): bool
    {
        return $user->can('replicate_class::major');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_class::major');
    }
}

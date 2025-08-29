<?php

namespace App\Policies;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BadgePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_badges::badge');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Badge $badge): bool
    {
        return $user->can('view_badges::badge');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_badges::badge');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Badge $badge): bool
    {
        return $user->can('update_badges::badge');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Badge $badge): bool
    {
        return $user->can('delete_badges::badge');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_badges::badge');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Badge $badge): bool
    {
        return $user->can('force_delete_badges::badge');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_badges::badge');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Badge $badge): bool
    {
        return $user->can('restore_badges::badge');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_badges::badge');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Badge $badge): bool
    {
        return $user->can('replicate_badges::badge');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_badges::badge');
    }
}

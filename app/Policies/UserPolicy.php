<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(RoleEnum::Admin->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Admin can view anyone, or a user can view their own profile.
        return $user->hasRole(RoleEnum::Admin->value) || $user->is($model);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(RoleEnum::Admin->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // No one can edit an admin user.
        if ($model->hasRole(RoleEnum::Admin->value)) {
            return false;
        }

        // Only admins can edit other users (who are not admins).
        return $user->hasRole(RoleEnum::Admin->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Cannot delete an admin or yourself.
        if ($model->hasRole(RoleEnum::Admin->value) || $user->is($model)) {
            return false;
        }

        return $user->hasRole(RoleEnum::Admin->value);
    }
}

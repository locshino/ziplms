<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use OwenIt\Auditing\Models\Audit;

class AuditPolicy
{
    use HandlesAuthorization;

    /**
     * Kiểm tra quyền xem audit
     */
    public function audit(User $user, ?Audit $audit = null): bool
    {
        return $user->hasRole('super_admin') || $user->hasPermissionTo('view_audit');
    }

    /**
     * Kiểm tra quyền khôi phục audit
     */
    public function restoreAudit(User $user, ?Audit $audit = null): bool
    {
        return $user->hasRole('super_admin') || $user->hasPermissionTo('restore_audit');
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use OwenIt\Auditing\Models\Audit;

class AuditPolicy
{
    use HandlesAuthorization;

    public function audit(User $user, ?Audit $audit = null): bool
    {
        return $user->can('view_audit');
    }

    public function restoreAudit(User $user, ?Audit $audit = null): bool
    {
        return $user->can('restore_audit');
    }
}

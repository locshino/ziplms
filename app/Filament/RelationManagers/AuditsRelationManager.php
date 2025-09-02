<?php

namespace App\Filament\RelationManagers;

use App\Libs\Roles\RoleHelper;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager as BaseAuditsRelationManager;

class AuditsRelationManager extends BaseAuditsRelationManager
{
    public static function canViewForRecord($ownerRecord, $pageClass): bool
    {
        return RoleHelper::isAdministrative();
    }
}

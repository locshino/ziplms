<?php

namespace App\Filament\Pages\Auth;

use App\Libs\Roles\RoleHelper;
use Filament\Auth\Pages\EditProfile as FilamentEditProfile;
use Filament\Schemas\Components\Component;

class EditProfile extends FilamentEditProfile
{
    // protected string $view = 'filament.pages.auth.edit-profile';

    protected function getNameFormComponent(): Component
    {
        return parent::getNameFormComponent()
            ->disabled(RoleHelper::isAdministrative() == false);
    }

    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()
            ->disabled(RoleHelper::isAdministrative() == false);
    }
}

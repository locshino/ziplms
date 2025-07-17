<?php

namespace App\Enums\Contracts;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

interface HasFilamentEnumStyle extends HasColor, HasDescription, HasIcon, HasLabel
{
    //
}

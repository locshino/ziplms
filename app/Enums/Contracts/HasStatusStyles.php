<?php

namespace App\Enums\Contracts;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Interface for status enums that provides styling capabilities
 * including label, description, icon, and color support for Filament components.
 */
interface HasStatusStyles extends HasColor, HasDescription, HasIcon, HasLabel
{
    //
}

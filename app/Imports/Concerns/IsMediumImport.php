<?php

namespace App\Imports\Concerns;

/**
 * A marker interface to indicate that the import involves a medium-sized dataset.
 *
 * This serves as a balanced default for most import tasks.
 */
interface IsMediumImport
{
    // This interface is intentionally empty.
    // It serves only as a marker for the base ExcelImporter.
}

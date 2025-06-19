<?php

namespace App\Imports\Concerns;

/**
 * A marker interface to indicate that the import involves a large dataset.
 *
 * Importers implementing this concern will use larger, optimized batch and chunk sizes,
 * suitable for files with tens of thousands of rows.
 */
interface IsBigImport
{
    // This interface is intentionally empty.
}

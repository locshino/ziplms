<?php

namespace App\Imports\Concerns;

/**
 * A marker interface to indicate that the import involves a small dataset.
 *
 * Importers implementing this concern will automatically use smaller, optimized
 * batch and chunk sizes, suitable for quick processing without significant overhead.
 */
interface IsSmallImport
{
    // This interface is intentionally empty.
    // It serves only as a marker for the base ExcelImporter.
}

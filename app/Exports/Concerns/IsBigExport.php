<?php

namespace App\Exports\Concerns;

/**
 * A marker interface to indicate that the export involves a large dataset.
 *
 * Exports implementing this concern will use larger, optimized batch and chunk sizes,
 * suitable for files with tens of thousands of rows.
 */
interface IsBigExport
{
    // This interface is intentionally empty.
}

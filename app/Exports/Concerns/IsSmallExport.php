<?php

namespace App\Exports\Concerns;

/**
 * A marker interface to indicate that the export involves a small dataset.
 *
 * Exports implementing this concern will automatically use smaller, optimized
 * batch and chunk sizes, suitable for quick processing without significant overhead.
 */
interface IsSmallExport
{
    // This interface is intentionally empty.
    // It serves only as a marker for the base ExcelExport.
}

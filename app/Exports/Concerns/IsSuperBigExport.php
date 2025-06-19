<?php

namespace App\Exports\Concerns;

/**
 * A marker interface to indicate that the export involves a very large or "super-sized" dataset.
 *
 * This preset is for extremely large files (e.g., 100,000+ rows), using the largest
 * chunk and batch sizes to maximize throughput.
 */
interface IsSuperBigExport
{
    // This interface is intentionally empty.
}

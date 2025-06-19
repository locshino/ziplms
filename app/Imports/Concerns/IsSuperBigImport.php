<?php

namespace App\Imports\Concerns;

/**
 * A marker interface to indicate that the import involves a very large or "super-sized" dataset.
 *
 * This preset is for extremely large files (e.g., 100,000+ rows), using the largest
 * chunk and batch sizes to maximize throughput.
 */
interface IsSuperBigImport
{
    // This interface is intentionally empty.
}

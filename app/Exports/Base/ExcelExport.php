<?php

namespace App\Exports\Base;

use App\Exports\Concerns\IsBigExport;
use App\Exports\Concerns\IsMediumExport;
use App\Exports\Concerns\IsSmallExport;
use App\Exports\Concerns\IsSuperBigExport;
use pxlrbt\FilamentExcel\Exports\ExcelExport as BasePxlrbtExport;

/**
 * Base Export Class - extending the pxlrbt/filament-excel package.
 *
 * This class provides a flexible template for all export classes. It encourages using the
 * simple, declarative `withColumns()` method inside `setUp()` for most cases.
 * For complex exports, child classes can implement Maatwebsite concerns like
 * `WithHeadings` and `WithMapping` and define the required methods.
 *
 * It defaults to a queued export for stability and leverages performance presets.
 */
class ExcelExport extends BasePxlrbtExport
{
    /**
     * Determines if the export should be queued by default.
     * Child classes can override this property to `false` for instant downloads.
     */
    protected bool $shouldQueue = true;

    /**
     * An initialization method that is called automatically.
     * It sets up the default behavior for queuing and performance.
     * Child classes should override this method to define their export columns or other settings.
     */
    public function setUp(): void
    {
        // Set default behavior. Child classes will add their own logic here,
        // for example: $this->withColumns([...]);

        if ($this->shouldQueue) {
            $this->queue();
        }

        $this->withChunkSize($this->getPerformancePresetSize());
    }

    /**
     * Determines the optimal size for chunks based on the
     * concerns implemented by the child class.
     */
    private function getPerformancePresetSize(): int
    {
        return match (true) {
            $this instanceof IsSmallExport => 100,
            $this instanceof IsMediumExport => 500,
            $this instanceof IsBigExport => 1000,
            $this instanceof IsSuperBigExport => 2000,
            default => 500, // The default size
        };
    }
}

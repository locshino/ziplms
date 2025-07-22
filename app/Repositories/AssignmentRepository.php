<?php

namespace App\Repositories;

use App\Models\Assignment;

class AssignmentRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getInstructionsText(Assignment $assignment): ?string
    {
        $vi = $assignment->getTranslation('instructions', 'vi');

        return is_array($vi) ? ($vi['text'] ?? null) : null;
    }

    public function getInstructionsFileUrl(Assignment $assignment): ?string
    {
        $vi = $assignment->getTranslation('instructions', 'vi');
        $filePath = is_array($vi) ? ($vi['file'] ?? $vi['en'] ?? null) : null;

        return $filePath ? asset('storage/'.$filePath) : null;
    }

    public function getInstructionsFileDefault(Assignment $assignment): ?string
    {
        $vi = $assignment->getTranslation('instructions', 'vi');

        return is_array($vi) ? ($vi['file'] ?? $vi['en'] ?? null) : null;
    }

    public function shouldShowInstructionsFile(Assignment $assignment): bool
    {
        $vi = $assignment->getTranslation('instructions', 'vi');

        return is_array($vi) && (! empty($vi['file'] ?? null) || ! empty($vi['en'] ?? null));
    }
}

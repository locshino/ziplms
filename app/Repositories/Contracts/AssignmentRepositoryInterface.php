<?php

namespace App\Repositories\Contracts;

use App\Models\Assignment;
use App\Repositories\Contracts\Base\RepositoryInterface;

interface AssignmentRepositoryInterface extends RepositoryInterface
{
    public function getInstructionsText(Assignment $assignment): ?string;

    public function getInstructionsFileUrl(Assignment $assignment): ?string;

    public function getInstructionsFileDefault(Assignment $assignment): ?string;

    public function shouldShowInstructionsFile(Assignment $assignment): bool;
}

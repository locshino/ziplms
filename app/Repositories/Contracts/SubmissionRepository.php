<?php

namespace App\Repositories\Contracts;

use App\Models\Assignment;
use App\Repositories\Contracts\Base\RepositoryInterface;

interface SubmissionRepository extends RepositoryInterface
{
    public function submitAssignment(Assignment $assignment, array $data, int $userId);
}

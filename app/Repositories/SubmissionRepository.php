<?php

namespace App\Repositories;

use App\Models\Assignment;
use App\Repositories\Base\Repository;
use App\Repositories\Contracts\SubmissionRepository as SubmissionRepositoryInterface;

class SubmissionRepository extends Repository implements SubmissionRepositoryInterface
{
    public function model(): string
    {
        return Assignment::class;
    }

    public function submitAssignment(Assignment $assignment, array $data, $userId)
    {

        $submis = $assignment->submissions()->create([
            'user_id' => $userId,
            'submission_text' => $data['submission_file'],
            'status' => 'submitted',
        ]);
        $submis->grade()->create([
            'submission_id' => $submis->id,
            'grade' => 0,
            'feedback' => $data['feedback'] ?? null,
            'graded_by' => $userId,
        ]);

        return $submis;
    }
}

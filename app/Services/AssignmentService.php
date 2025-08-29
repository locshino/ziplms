<?php

namespace App\Services;

use App\Repositories\Interfaces\AssignmentRepositoryInterface;
use App\Services\Interfaces\AssignmentServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssignmentService extends BaseService implements AssignmentServiceInterface
{
    public function __construct(
        private AssignmentRepositoryInterface $assignmentRepository
    ) {
        parent::__construct($assignmentRepository);
    }

    public function getAssignmentsForStudent(array $filters): LengthAwarePaginator
    {
        // FIX: Ensure studentId is always a string from Auth::id()
        $studentId = Auth::id();

        return $this->assignmentRepository->getStudentAssignments(
            $studentId,
            $filters['courseId'] ?? null,
            $filters['filter'] ?? 'all',
            $filters['search'] ?? null
        );
    }

    public function submitAssignment(
        string $assignmentId,
        string $studentId,
        string $submissionType,
        array $data,
        ?UploadedFile $file
    ): Model {
        return DB::transaction(function () use ($assignmentId, $studentId, $submissionType, $data, $file) {
            $submission = $this->assignmentRepository->createSubmission($assignmentId, $studentId);

            $notes = $data['notes'] ?? null;

            if ($submissionType === 'file' && $file) {
                $media = $submission->addMedia($file)->toMediaCollection('submission_files');
                if ($notes) {
                    $media->setCustomProperty('notes', $notes)->save();
                }
            } elseif ($submissionType === 'link') {
                $link_url = $data['link_url'] ?? 'No link provided.';
                $content = 'Submission Link: '.$link_url;
                if ($notes) {
                    $content .= "\n\nNotes From Student:\n".$notes;
                }
                $submission->addMediaFromString($content)
                    ->usingFileName('link_submission_for_assignment_'.$assignmentId.'.txt')
                    ->toMediaCollection('submission_files');
            }

            return $submission;
        });
    }
}

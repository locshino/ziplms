<?php

namespace App\Filament\Pages;

use App\Models\Assignment;
use App\Models\Course;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class MyDocument extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.my-document';

    protected static ?string $navigationLabel = 'Tài liệu của tôi';

    protected static ?string $title = 'Tài liệu của tôi';

    public ?string $selectedCourseId = null;

    public function mount(): void
    {
        // Initialize any required data
    }

    /**
     * Get all documents (assignments with media files) available to the current user
     */
    public function getDocuments(): Collection
    {
        $user = Auth::user();

        // Get courses the user is enrolled in
        $enrolledCourseIds = $user->enrollments()->pluck('course_id');

        $query = Assignment::with(['course', 'media'])
            ->whereIn('course_id', $enrolledCourseIds)
            ->whereHas('media') // Only assignments that have attached files
            ->orderBy('created_at', 'desc');

        // Apply course filter
        if ($this->selectedCourseId) {
            $query->where('course_id', $this->selectedCourseId);
        }

        return $query->get();
    }

    /**
     * Get courses that the user is enrolled in
     */
    public function getEnrolledCourses(): Collection
    {
        $user = Auth::user();

        return Course::whereHas('enrollments', function ($query) use ($user) {
            $query->where('student_id', $user->id);
        })->get();
    }

    /**
     * Get total count of documents
     */
    public function getTotalDocumentsCount(): int
    {
        return $this->getDocuments()->count();
    }

    /**
     * Get count of documents by course
     */
    public function getDocumentsByCourseCount(): array
    {
        $documents = $this->getDocuments();
        $courseStats = [];

        foreach ($documents->groupBy('course_id') as $courseId => $courseDocuments) {
            $course = $courseDocuments->first()->course;
            $courseStats[] = [
                'course_name' => $course->title,
                'count' => $courseDocuments->count(),
            ];
        }

        return $courseStats;
    }

    /**
     * Clear all filters
     */
    public function clearFilters(): void
    {
        $this->selectedCourseId = null;
    }

    /**
     * Get document type based on assignment
     */
    public function getDocumentType(Assignment $assignment): string
    {
        // Simple classification based on title or content
        $title = strtolower($assignment->title);

        if (str_contains($title, 'bài tập') || str_contains($title, 'assignment')) {
            return 'assignment';
        } elseif (str_contains($title, 'tài liệu') || str_contains($title, 'document')) {
            return 'document';
        } elseif (str_contains($title, 'hướng dẫn') || str_contains($title, 'guide')) {
            return 'guide';
        }

        return 'general';
    }

    /**
     * Get document type label
     */
    public function getDocumentTypeLabel(string $type): string
    {
        return match ($type) {
            'assignment' => 'Bài tập',
            'document' => 'Tài liệu',
            'guide' => 'Hướng dẫn',
            'general' => 'Tổng quát',
            default => 'Khác'
        };
    }

    /**
     * Action to view document details
     */
    public function viewDocumentAction(): Action
    {
        return Action::make('viewDocument')
            ->label('Xem chi tiết')
            ->icon('heroicon-o-eye')
            ->color('info')
            ->action(function (array $arguments) {
                $assignmentId = $arguments['assignment_id'];
                // Redirect to assignment details or open modal
                $this->redirect(route('filament.admin.resources.assignments.view', $assignmentId));
            });
    }

    /**
     * Action to download document
     */
    public function downloadDocumentAction(): Action
    {
        return Action::make('downloadDocument')
            ->label('Tải xuống')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->url(function (array $arguments) {
                $assignmentId = $arguments['assignment_id'];
                $mediaId = $arguments['media_id'] ?? null;

                $assignment = Assignment::findOrFail($assignmentId);

                if ($mediaId) {
                    // Download specific media file
                    $media = $assignment->getMedia()->where('id', $mediaId)->first();
                    if ($media) {
                        return route('media.download', $media);
                    }
                } else {
                    // Download first media file if no specific media ID
                    $media = $assignment->getFirstMedia();
                    if ($media) {
                        return route('media.download', $media);
                    }
                }

                return null;
            })
            ->openUrlInNewTab(false);
    }

    /**
     * Action to view/preview document
     */
    public function previewDocumentAction(): Action
    {
        return Action::make('previewDocument')
            ->label('Xem trước')
            ->icon('heroicon-o-document-text')
            ->color('warning')
            ->url(function (array $arguments) {
                $assignmentId = $arguments['assignment_id'];
                $mediaId = $arguments['media_id'] ?? null;

                $assignment = Assignment::findOrFail($assignmentId);

                if ($mediaId) {
                    $media = $assignment->getMedia()->where('id', $mediaId)->first();
                } else {
                    $media = $assignment->getFirstMedia();
                }

                if ($media) {
                    return $media->getUrl();
                }

                return null;
            })
            ->openUrlInNewTab(true);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}

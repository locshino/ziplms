<?php

namespace App\Filament\Pages;

use App\Models\Assignment;
use App\Models\Course;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MyDocument extends Page
{
    use HasPageShield;

    protected static bool $shouldRegisterNavigation = false;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'filament.pages.my-document';

    public static function getNavigationLabel(): string
    {
        return __('pages.my_document');
    }

    public function getTitle(): string
    {
        return __('pages.my_document');
    }

    protected static ?string $title = 'Tài liệu của tôi';

    public ?string $selectedCourseId = null;

    public function mount(): void
    {
        // Initialize any required data
    }

    /**
     * Lấy tất cả tài liệu (media files) từ các khóa học hợp lệ của người dùng.
     * Phương thức này sử dụng lại getEnrolledCourses() để tránh lặp code.
     */
    public function getDocuments(): Collection
    {
        // ✅ Gọi lại phương thức đã có để lấy danh sách khóa học
        $enrolledCourses = $this->getEnrolledCourses();

        $documents = new Collection;
        foreach ($enrolledCourses as $course) {
            // Áp dụng bộ lọc nếu người dùng chọn một khóa học cụ thể
            if ($this->selectedCourseId && $course->id !== $this->selectedCourseId) {
                continue;
            }
            // Chỉ lấy media từ collection 'course_documents'
            $courseMedia = $course->getMedia('course_documents');
            foreach ($courseMedia as $media) {
                // Gán thông tin khóa học vào media để dễ dàng truy cập trong view
                $media->course = $course;
                $documents->add($media);
            }
        }

        return $documents->sortByDesc('created_at');
    }

    /**
     * Lấy tài liệu được nhóm theo khóa học.
     */
    public function getDocumentsByCourse(): Collection
    {
        $enrolledCourses = $this->getEnrolledCourses();
        $courseDocuments = new Collection;

        foreach ($enrolledCourses as $course) {
            // Áp dụng bộ lọc nếu người dùng chọn một khóa học cụ thể
            if ($this->selectedCourseId && $course->id !== $this->selectedCourseId) {
                continue;
            }

            $courseMedia = $course->getMedia('course_documents');
            if ($courseMedia->isNotEmpty()) {
                $courseDocuments->push([
                    'course' => $course,
                    'documents' => $courseMedia->sortByDesc('created_at'),
                ]);
            }
        }

        return $courseDocuments;
    }

    /**
     * Lấy các khóa học mà người dùng đang tham gia và còn trong thời gian hợp lệ.
     * Đây là nơi duy nhất chứa logic truy vấn, đảm bảo tính nhất quán.
     */
    public function getEnrolledCourses(): Collection
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $userId = $user->id;
        $now = now();

        $courses = Course::whereHas('users', function ($query) use ($userId, $now) {
            $query->where('users.id', $userId)
                ->where(function ($q) use ($now) {
                    $q->whereNull('course_user.start_at')
                        ->orWhere('course_user.start_at', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('course_user.end_at')
                        ->orWhere('course_user.end_at', '>=', $now);
                });
        })
            ->with(['users' => function ($query) use ($userId) {
                $query->where('users.id', $userId)
                    ->withPivot('start_at', 'end_at');
            }])
            ->orderBy('title')
            ->get();

        return $courses;
    }

    /**
     * Lấy tổng số lượng tài liệu.
     */
    public function getTotalDocumentsCount(): int
    {
        return $this->getDocuments()->count();
    }

    /**
     * Lấy số lượng tài liệu theo từng khóa học.
     */
    public function getDocumentsByCourseCount(): array
    {
        $documents = $this->getDocuments();
        $courseStats = [];

        foreach ($documents->groupBy('model_id') as $courseId => $courseDocuments) {
            $course = $courseDocuments->first()->course;
            $courseStats[] = [
                'course_name' => $course->title,
                'count' => $courseDocuments->count(),
            ];
        }

        return $courseStats;
    }

    /**
     * Xóa bộ lọc khóa học.
     */
    public function clearFilters(): void
    {
        $this->selectedCourseId = null;
    }

    /**
     * Lấy loại tài liệu dựa trên phần mở rộng của file.
     */
    public function getDocumentType(Media $media): string
    {
        $extension = strtolower(pathinfo($media->file_name, PATHINFO_EXTENSION));
        $collectionName = strtolower($media->collection_name);

        if (in_array($extension, ['pdf', 'doc', 'docx', 'txt'])) {
            return 'document';
        } elseif (in_array($extension, ['mp4', 'mov', 'avi', 'mkv'])) {
            return 'video';
        } elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            return 'image';
        } elseif (str_contains($collectionName, 'assignment')) {
            return 'assignment';
        }

        return 'general';
    }

    /**
     * Lấy nhãn (label) của loại tài liệu.
     */
    public function getDocumentTypeLabel(string $type): string
    {
        return match ($type) {
            'assignment' => 'Bài tập',
            'document' => 'Tài liệu',
            'video' => 'Video',
            'image' => 'Hình ảnh',
            'general' => 'Tổng quát',
            default => 'Khác'
        };
    }

    /**
     * Lấy URL để tải file.
     */
    public function getDownloadUrl(Media $media): string
    {
        return $media->getUrl();
    }

    /**
     * Lấy dung lượng file dưới định dạng dễ đọc.
     */
    public function getHumanReadableSize(Media $media): string
    {
        $bytes = $media->size;
        if ($bytes === 0) {
            return '0 B';
        }
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes, 1024));

        return round($bytes / (1024 ** $i), 2).' '.$units[$i];
    }

    /**
     * Lấy icon cho file dựa trên phần mở rộng.
     */
    public function getFileIcon(Media $media): string
    {
        $extension = strtolower(pathinfo($media->file_name, PATHINFO_EXTENSION));

        return match ($extension) {
            'pdf', 'doc', 'docx' => 'heroicon-o-document-text',
            'xls', 'xlsx' => 'heroicon-o-table-cells',
            'ppt', 'pptx' => 'heroicon-o-presentation-chart-line',
            'zip', 'rar' => 'heroicon-o-archive-box',
            'mp4', 'mov', 'avi', 'mkv' => 'heroicon-o-play-circle',
            'mp3', 'wav' => 'heroicon-o-musical-note',
            'jpg', 'jpeg', 'png', 'gif' => 'heroicon-o-photo',
            default => 'heroicon-o-document'
        };
    }

    /**
     * Action để xem chi tiết tài liệu (giả định liên kết đến Assignment).
     */
    public function viewDocumentAction(): Action
    {
        return Action::make('viewDocument')
            ->label('Xem chi tiết')
            ->icon('heroicon-o-eye')
            ->color('info')
            ->action(function (array $arguments) {
                $media = Media::find($arguments['media_id']);
                if ($media && $media->model_type === Assignment::class) {
                    $assignmentId = $media->model_id;
                    $this->redirect(route('filament.admin.resources.assignments.view', $assignmentId));
                }
            });
    }

    /**
     * Action để tải tài liệu.
     */
    public function downloadDocumentAction(): Action
    {
        return Action::make('downloadDocument')
            ->label('Tải xuống')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->url(function (array $arguments) {
                $media = Media::find($arguments['media_id']);

                return $media ? $media->getUrl() : null;
            })
            ->openUrlInNewTab(true);
    }

    /**
     * Action để xem trước tài liệu.
     */
    public function previewDocumentAction(): Action
    {
        return Action::make('previewDocument')
            ->label('Xem trước')
            ->icon('heroicon-o-document-text')
            ->color('warning')
            ->url(function (array $arguments) {
                $media = Media::find($arguments['media_id']);

                return $media ? $media->getUrl() : null;
            })
            ->openUrlInNewTab(true);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}

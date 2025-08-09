<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Services\Interfaces\UserServiceInterface;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class UserDetail extends Page
{
    // use HasPageShield;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.user-detail';

    public $activeTab = 'course_number';

    public $record;

    public int $courseCount = 0;

    public $enrolledCourses = [];

    public $courseService;

    public $badges;

    public $enrolledCoursesAll;

    public $selectedCourseId;

    public function mount($record): void
    {
        $this->record = User::findOrFail($record);
        if ($this->record->hasRole('student')) {
            $this->courseCount = $this->record->enrollments()->count();
        } elseif ($this->record->hasRole('teacher')) {
            $this->courseCount = $this->record->taughtCourses()->count();
        } else {
            $this->courseCount = 0;
        }

        $userRepository = app(UserServiceInterface::class);
        if ($this->record->hasRole('student')) {
            $this->enrolledCourses = $userRepository->getEnrolledCourses($this->record->id)->pluck('course');
        } elseif ($this->record->hasRole('teacher')) {
            $this->enrolledCourses = $userRepository->getEnrolledCourses($this->record->id);
        } else {
            $this->enrollments = collect();
        }
        $this->badges = $this->record->userBadges->pluck('badge');
        $this->enrolledCoursesAll = Course::all();

    }

    public function addUserToCourse()
    {
        if (! $this->selectedCourseId || ! $this->record) {
            return;
        }

        $courseId = $this->selectedCourseId;
        $userId = $this->record->id;
        $exists = $this->record->enrollments()
            ->where('course_id', $courseId)
            ->exists();
        if ($exists) {
            Notification::make()
                ->title('Người dùng đã được ghi danh vào khóa học này')
                ->warning()
                ->send();

            return;
        }

        if (! $exists) {
            Enrollment::create([
                'student_id' => $userId,
                'course_id' => $courseId,
            ]);
        }
        Notification::make()
            ->title('Thêm người dùng vào khóa học thành công')
            ->success()
            ->send();
        $userRepository = app(UserServiceInterface::class);
        $this->enrolledCourses = $userRepository
            ->getEnrolledCourses($this->record->id)
            ->pluck('course');
        $this->selectedCourseId = null;
    }

    public $name;

    public $email;

    public $avatarFile;

    public function updateUser()
    {
        $data = [
            'name' => $this->name ?: $this->record->name,
            'email' => $this->email ?: $this->record->email,
        ];

        $userService = app(UserServiceInterface::class);

        $userService->updateUserInfo($this->record, $data, $this->avatarFile);

        Notification::make()
            ->title('Cập nhật thông tin người dùng thành công')
            ->success()
            ->send();

        $this->record = $this->record->fresh();

        $this->avatarFile = null;
    }
}

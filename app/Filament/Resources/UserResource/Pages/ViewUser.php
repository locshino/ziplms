<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function addUserToCourse()
    {
        if (! $this->selectedCourseId || ! $this->record) {
            return;
        }

        $courseId = $this->selectedCourseId;
        $userId = $this->record->id;

        // Kiểm tra nếu đã tồn tại thì không thêm nữa
        $exists = Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();

        if (! $exists) {
            Enrollment::create([
                'user_id' => $userId,
                'course_id' => $courseId,
            ]);
        }

        // Reset dropdown
        $this->selectedCourseId = null;

        // Reload danh sách khóa học của user (nếu có hiển thị ở dưới)
        $this->enrolledCourses = $this->userRepository->getEnrolledCourses($userId);
    }
}

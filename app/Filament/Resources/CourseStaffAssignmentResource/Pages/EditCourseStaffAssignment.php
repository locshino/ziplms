<?php

namespace App\Filament\Resources\CourseStaffAssignmentResource\Pages;

use App\Filament\Resources\CourseStaffAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * @property-read \App\Models\CourseStaffAssignment $record
 */
class EditCourseStaffAssignment extends EditRecord
{
    protected static string $resource = CourseStaffAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->record->load('tags');
        
        $data['role_tag'] = $this->record->tags->first()?->name;

        return $data;
    }

    /**
     * Thêm phương thức này vào.
     * Nó sẽ được thực thi trước khi lưu bản ghi.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Kiểm tra xem có giá trị role_tag được gửi lên không
        if (isset($data['role_tag'])) {
            // Cập nhật tag (vai trò) cho bản ghi hiện tại
            $this->record->syncTags([$data['role_tag']]);
        }

        // Xóa key 'role_tag' khỏi mảng data để Filament không cố gắng
        // lưu nó vào cột không tồn tại trong database.
        unset($data['role_tag']);

        // Trả về mảng data đã được xử lý
        return $data;
    }
}
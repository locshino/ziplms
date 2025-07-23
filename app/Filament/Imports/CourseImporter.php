<?php

namespace App\Filament\Imports;

use App\Models\Course;
use App\Models\Organization;
use App\States\Active;
use App\States\Inactive;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Validation\Rule;

class CourseImporter extends Importer
{
    protected static ?string $model = Course::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->label('Mã Môn Học')
                ->rules(['required', 'max:255', 'unique:courses,code']),
            ImportColumn::make('name_vi')
                ->label('Tên Môn Học (Tiếng Việt)')
                ->rules(['required', 'max:255']),
            ImportColumn::make('description_vi')
                ->label('Mô Tả (Tiếng Việt)')
                ->rules(['nullable']),
            ImportColumn::make('organization_name')
                ->label('Tên Tổ Chức')
                ->rules(['required', 'exists:organizations,name']),
            ImportColumn::make('parent_code')
                ->label('Mã Môn Học Cha')
                ->rules(['nullable', 'exists:courses,code']),
            ImportColumn::make('start_date')
                ->label('Ngày Bắt Đầu')
                ->rules(['nullable', 'date']),
            ImportColumn::make('end_date')
                ->label('Ngày Kết Thúc')
                ->rules(['nullable', 'date', 'after_or_equal:start_date']),
            ImportColumn::make('status')
                ->label('Trạng Thái')
                ->rules(['required', Rule::in([Active::$name, Inactive::$name])]),
        ];
    }

    // Xử lý logic chính: tìm hoặc tạo mới record từ mỗi dòng trong file
    public function resolveRecord(): ?Course
    {
        // Tìm hoặc tạo mới Course dựa trên `code` để tránh trùng lặp
        $course = Course::firstOrNew(['code' => $this->data['code']]);

        // Tìm ID của Tổ chức và Môn học cha
        $organization = Organization::where('name', $this->data['organization_name'])->first();
        $parentCourse = Course::where('code', $this->data['parent_code'])->first();

        // Gán dữ liệu
        $course->organization_id = $organization?->id;
        $course->parent_id = $parentCourse?->id;
        $course->start_date = $this->data['start_date'];
        $course->end_date = $this->data['end_date'];
        $course->status = $this->data['status'];

        // Set giá trị cho các trường đa ngôn ngữ
        $course->setTranslation('name', 'vi', $this->data['name_vi']);
        $course->setTranslation('description', 'vi', $this->data['description_vi']);

        return $course;
    }

    // Tùy chỉnh thông báo khi nhập file thành công
    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Nhập thành công '.number_format($import->successful_rows).' '.str('dòng')->plural($import->successful_rows).'.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('dòng')->plural($failedRowsCount).' đã bị lỗi.';
        }

        return $body;
    }
}

<?php

// app/Repositories/UserClassMajorEnrollmentRepository.php

namespace App\Repositories;

use App\Models\ClassesMajor; // Model đại diện cho bảng lớp/chuyên ngành
use App\Models\UserClassMajorEnrollment; // Interface của repository
use App\Repositories\Base\Repository; // Builder cho câu truy vấn Eloquent
use App\Repositories\Contracts\UserClassMajorEnrollmentRepositoryInterface; // Repository base chung
use Illuminate\Database\Eloquent\Builder; // Model UserClassMajorEnrollment đại diện bảng đăng ký lớp/chuyên ngành

class UserClassMajorEnrollmentRepository extends Repository implements UserClassMajorEnrollmentRepositoryInterface
{
    // Xác định model chính mà repository này sẽ thao tác
    protected function model(): string
    {
        return UserClassMajorEnrollment::class;
    }

    // Lấy danh sách các lớp/chuyên ngành để làm tùy chọn filter
    public function getClassMajorFilterOptions(): array
    {
        return ClassesMajor::query()->pluck('name', 'id')->toArray();
    }

    /**
     * Áp dụng bộ lọc theo class_major_id lên truy vấn
     *
     * @param  Builder  $query  Đối tượng truy vấn Eloquent
     * @param  mixed  $classMajorId  ID lớp/chuyên ngành cần lọc
     * @return Builder Truy vấn đã áp dụng bộ lọc
     */
    public function applyClassMajorFilter(Builder $query, $classMajorId): Builder
    {
        if (! empty($classMajorId)) {
            // Thêm điều kiện where để lọc theo class_major_id
            $query->where('class_major_id', $classMajorId);
        }

        return $query;
    }
}

<?php

namespace App\Repositories\Contracts;

use App\Models\Course;
use App\Repositories\Contracts\Base\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Interface CourseRepositoryInterface
 */
interface CourseRepositoryInterface extends RepositoryInterface
{
    /**
     * Lấy nhãn (label) của trạng thái (status) cho một khóa học.
     */
    public function getCourseStatusLabel(Course $course): string;

    /**
     * Lấy màu tương ứng với trạng thái của khóa học.
     */
    public function getCourseStatusColor(Course $course): string;

    /**
     * Lấy danh sách các khóa học cha có thể chọn.
     */
    public function getAvailableParents(?int $excludeId = null): Collection;

    /**
     * Áp dụng bộ lọc ngày tạo vào câu truy vấn.
     */
    public function applyDateFilter(Builder $query, ?string $from, ?string $to): Builder;
}
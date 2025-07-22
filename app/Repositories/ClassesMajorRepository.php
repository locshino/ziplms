<?php

namespace App\Repositories;

use App\Models\ClassesMajor;
use App\Repositories\Base\Repository;
use App\Repositories\Contracts\ClassesMajorRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ClassesMajorRepository
 */
class ClassesMajorRepository extends Repository implements ClassesMajorRepositoryInterface
{
    /**
     * Xác định tên class model sẽ sử dụng.
     */
    protected function model(): string
    {
        return ClassesMajor::class;
    }

    /**
     * Lấy danh sách tùy chọn cho dropdown chọn đơn vị cha (parent).
     *
     * @return array<int, string>
     */
    public function getParentOptions(): array
    {
        return $this->query()
            ->select('id', 'name')
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * Áp dụng bộ lọc theo parent ID.
     *
     * @param  Builder  $query  Đối tượng truy vấn Eloquent
     * @param  int|string|null  $parentId  ID đơn vị cha cần lọc
     * @return Builder Truy vấn đã được áp dụng bộ lọc
     */
    public function applyParentFilter(Builder $query, $parentId): Builder
    {
        if (! empty($parentId)) {
            $query->where('parent_id', $parentId);
        }

        return $query;
    }
}

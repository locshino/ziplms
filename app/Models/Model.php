<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str; // Quan trọng: Thêm dòng import này

abstract class Model extends EloquentModel
{
    /**
     * Các thuộc tính không được gán hàng loạt.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Ghi đè phương thức boot của model để gán UUID tự động.
     * Phương thức này sẽ được kế thừa bởi tất cả các model con.
     */
    protected static function booted(): void
    {
        // Sử dụng sự kiện 'creating' để tạo UUID trước khi lưu vào database
        static::creating(function ($model) {
            // Chỉ gán UUID nếu khóa chính (id) chưa được set
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Báo cho Eloquent rằng ID không phải là số tự tăng.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Báo cho Eloquent biết kiểu dữ liệu của khóa chính là chuỗi.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }
}

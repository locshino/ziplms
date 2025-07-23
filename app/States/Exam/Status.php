<?php

namespace App\States\Exam;

use App\States\Base;
use Spatie\ModelStates\StateConfig;

abstract class Status extends Base\State
{
    /**
     * Lấy nhãn hiển thị cho trạng thái.
     */
    abstract public static function label(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            // Trạng thái mặc định khi một bài thi được tạo là 'Không hoạt động'
            ->default(Inactive::class)

            // Cho phép chuyển từ 'Không hoạt động' sang 'Hoạt động'
            ->allowTransition(Inactive::class, Active::class)

            // Cho phép chuyển ngược lại từ 'Hoạt động' sang 'Không hoạt động'
            ->allowTransition(Active::class, Inactive::class)

            // Khi bài thi 'Hoạt động', thí sinh có thể bắt đầu làm bài
            ->allowTransition(Active::class, InProgress::class)

            // Khi 'Đang diễn ra', có thể chuyển sang 'Đã hoàn thành'
            ->allowTransition(InProgress::class, Completed::class)

            // Hoặc chuyển sang 'Đã hủy'
            ->allowTransition(InProgress::class, Cancelled::class);
    }

    public static function getStates(): array
    {
        return [
            Active::class,
            Inactive::class,
            InProgress::class,
            Completed::class,
            Cancelled::class,
        ];
    }
}

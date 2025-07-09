<?php
// File: app/States/Exam/Status.php
// ---------------------------------
// File cấu hình chính cho các trạng thái.
// Luồng chuyển đổi được định nghĩa ở đây:
// Inactive <-> Active -> InProgress -> (Completed | Cancelled)

namespace App\States\Exam;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

// Import các lớp trạng thái
use App\States\Exam\Active;
use App\States\Exam\Inactive;
use App\States\Exam\InProgress;
use App\States\Exam\Completed;
use App\States\Exam\Cancelled;

abstract class Status extends State
{
    /**
     * Lấy nhãn hiển thị cho trạng thái.
     */
    abstract public static function label(): string;

    /**
     * Lấy màu sắc tương ứng với trạng thái (hữu ích cho UI).
     */
    abstract public function color(): string;

    /**
     * Cấu hình các trạng thái và các quy tắc chuyển đổi.
     */
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
}

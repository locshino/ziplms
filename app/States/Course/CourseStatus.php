<?php

namespace App\States\Course;

use App\States\Base\State;
use Spatie\ModelStates\StateConfig;

abstract class CourseStatus extends State
{
    public static string $langFile = 'states_course_status';

    public static function config(): StateConfig
    {
        return parent::config()
            // Trạng thái mặc định khi tạo mới là 'Chờ duyệt'.
            ->default(Pending::class)

            // 'Chờ duyệt' có thể được chấp thuận và chuyển sang 'Hoạt động'.
            ->allowTransition(Pending::class, Active::class)
            
            // 'Chờ duyệt' cũng có thể bị 'Hoãn'.
            ->allowTransition(Pending::class, Postponed::class)

            // 'Hoạt động' có thể bắt đầu và chuyển sang 'Đang tiến hành'.
            ->allowTransition(Active::class, InProgress::class)
            
            // 'Hoạt động' cũng có thể bị 'Hoãn'.
            ->allowTransition(Active::class, Postponed::class)

            // 'Hoạt động' có thể bị vô hiệu hóa.
            ->allowTransition(Active::class, Inactive::class)

            // 'Đang tiến hành' sẽ chuyển thành 'Hoàn thành' khi kết thúc.
            ->allowTransition(InProgress::class, Completed::class)
            
            // 'Đang tiến hành' cũng có thể bị 'Hoãn'.
            ->allowTransition(InProgress::class, Postponed::class)
            
            // 'Bị hoãn' có thể được lên lịch lại và quay về 'Hoạt động'.
            ->allowTransition(Postponed::class, Active::class)

            // 'Không hoạt động' có thể được kích hoạt lại.
            ->allowTransition(Inactive::class, Active::class)

            // 'Hoàn thành' có thể được 'Lưu trữ'.
            ->allowTransition(Completed::class, Archived::class);
    }

    /**
     * Lấy tất cả các trạng thái có thể có từ các file bạn cung cấp.
     */
    public static function getStates(): array
    {
        return [
            Pending::class,
            Active::class,
            InProgress::class,
            Postponed::class,
            Inactive::class, // Thêm mới
            Completed::class,
            Archived::class,
        ];
    }

    public static function getOptions(): array
    {
        $options = [];
        foreach (static::getStates() as $state) {
            $options[$state::$name] = $state::label();
        }
        return $options;
    }
}
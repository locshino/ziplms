<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Course Repository Exception Messages
    |--------------------------------------------------------------------------
    |
    | Các thông báo lỗi sau được sử dụng cho các exception cụ thể của
    | CourseRepository được ném ra bởi lớp CourseRepository.
    |
    */

    'course_not_found' => 'Không tìm thấy khóa học.',
    'course_not_found_with_id' => 'Không tìm thấy khóa học với ID :id.',
    'course_title_required' => 'Tiêu đề khóa học là bắt buộc.',
    'course_title_too_long' => 'Tiêu đề khóa học quá dài.',
    'course_description_required' => 'Mô tả khóa học là bắt buộc.',
    'invalid_instructor' => 'Giảng viên không hợp lệ.',
    'instructor_not_found' => 'Không tìm thấy giảng viên với ID :id.',
    'course_already_published' => 'Khóa học đã được xuất bản.',
    'course_not_published' => 'Khóa học chưa được xuất bản.',
    'cannot_delete_published_course' => 'Không thể xóa khóa học đã xuất bản.',
    'course_has_enrollments' => 'Không thể xóa khóa học có học viên đăng ký.',
    'course_has_assignments' => 'Không thể xóa khóa học có bài tập.',
    'course_has_quizzes' => 'Không thể xóa khóa học có bài kiểm tra.',
    'invalid_course_status' => 'Trạng thái khóa học không hợp lệ.',
    'invalid_course_category' => 'Danh mục khóa học không hợp lệ.',
    'course_capacity_exceeded' => 'Vượt quá sức chứa của khóa học.',
    'course_enrollment_closed' => 'Đăng ký khóa học đã đóng.',
    'course_not_available' => 'Khóa học không khả dụng để đăng ký.',
    'duplicate_course_title' => 'Tiêu đề khóa học đã tồn tại.',
    'invalid_course_duration' => 'Thời lượng khóa học không hợp lệ.',
    'invalid_course_price' => 'Giá khóa học không hợp lệ.',
    'course_start_date_invalid' => 'Ngày bắt đầu khóa học không hợp lệ.',
    'course_end_date_invalid' => 'Ngày kết thúc khóa học không hợp lệ.',
    'course_dates_conflict' => 'Ngày bắt đầu phải trước ngày kết thúc.',
    'create_course_failed' => 'Không thể tạo khóa học.',
    'update_course_failed' => 'Không thể cập nhật khóa học.',
    'delete_course_failed' => 'Không thể xóa khóa học.',
    'publish_course_failed' => 'Không thể xuất bản khóa học.',
    'unpublish_course_failed' => 'Không thể hủy xuất bản khóa học.',
];
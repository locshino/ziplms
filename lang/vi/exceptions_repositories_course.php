<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Course Repository Exception Language Lines (Vietnamese)
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the course repository exception
    | classes to provide localized error messages for course-related operations.
    |
    */

    'course_not_found' => 'Không tìm thấy khóa học.',
    'course_not_found_with_id' => 'Không tìm thấy khóa học với ID :id.',

    'course_not_published' => 'Khóa học chưa được xuất bản.',
    'course_not_published_with_id' => 'Khóa học với ID :id chưa được xuất bản.',

    'course_not_available' => 'Khóa học không khả dụng.',
    'course_not_available_with_id' => 'Khóa học với ID :id không khả dụng.',

    'enrollment_closed' => 'Đăng ký khóa học đã đóng.',
    'enrollment_closed_with_id' => 'Đăng ký đã đóng cho khóa học với ID :id.',

    'capacity_full' => 'Khóa học đã đầy.',
    'capacity_full_with_details' => 'Khóa học với ID :id đã đạt sức chứa tối đa :capacity học sinh.',

    'already_enrolled' => 'Học sinh đã đăng ký khóa học này.',
    'already_enrolled_with_details' => 'Học sinh :student_id đã đăng ký khóa học :course_id.',

    'not_enrolled' => 'Học sinh chưa đăng ký khóa học này.',
    'not_enrolled_with_details' => 'Học sinh :student_id chưa đăng ký khóa học :course_id.',

    'enrollment_failed' => 'Không thể đăng ký học sinh vào khóa học.',
    'enrollment_failed_with_details' => 'Không thể đăng ký học sinh :student_id vào khóa học :course_id: :reason',

    'unenrollment_failed' => 'Không thể hủy đăng ký học sinh khỏi khóa học.',
    'unenrollment_failed_with_details' => 'Không thể hủy đăng ký học sinh :student_id khỏi khóa học :course_id: :reason',

    'instructor_not_assigned' => 'Giảng viên chưa được phân công cho khóa học này.',
    'instructor_not_assigned_with_details' => 'Giảng viên :instructor_id chưa được phân công cho khóa học :course_id.',

    'instructor_already_assigned' => 'Giảng viên đã được phân công cho khóa học này.',
    'instructor_already_assigned_with_details' => 'Giảng viên :instructor_id đã được phân công cho khóa học :course_id.',

    'progress_calculation_failed' => 'Không thể tính toán tiến độ khóa học.',
    'progress_calculation_failed_with_details' => 'Không thể tính toán tiến độ cho khóa học :course_id: :reason',

    'statistics_calculation_failed' => 'Không thể tính toán thống kê khóa học.',
    'statistics_calculation_failed_with_reason' => 'Không thể tính toán thống kê khóa học: :reason',

    'insufficient_permissions' => 'Không đủ quyền để thực hiện thao tác này trên khóa học.',
    'insufficient_permissions_with_action' => 'Không đủ quyền để thực hiện thao tác khóa học: :action',
];

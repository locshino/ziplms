<?php

return [
    // Enrollment not found errors
    'enrollment_not_found' => 'Không tìm thấy đăng ký.',
    'enrollment_not_found_with_id' => 'Không tìm thấy đăng ký với ID :id.',
    'enrollment_not_found_for_user' => 'Không tìm thấy đăng ký của người dùng :user_id trong khóa học :course_id.',
    
    // Enrollment validation errors
    'user_required' => 'Người dùng là bắt buộc để đăng ký.',
    'course_required' => 'Khóa học là bắt buộc để đăng ký.',
    'invalid_user' => 'Người dùng không hợp lệ để đăng ký.',
    'invalid_course' => 'Khóa học không hợp lệ để đăng ký.',
    'user_not_found' => 'Không tìm thấy người dùng.',
    'course_not_found' => 'Không tìm thấy khóa học.',
    
    // Enrollment status errors
    'enrollment_already_exists' => 'Người dùng đã đăng ký khóa học này.',
    'enrollment_already_active' => 'Đăng ký đã được kích hoạt.',
    'enrollment_already_completed' => 'Đăng ký đã hoàn thành.',
    'enrollment_already_cancelled' => 'Đăng ký đã bị hủy.',
    'enrollment_not_active' => 'Đăng ký không hoạt động.',
    'enrollment_not_pending' => 'Đăng ký không ở trạng thái chờ.',
    'enrollment_expired' => 'Đăng ký đã hết hạn.',
    'invalid_enrollment_status' => 'Trạng thái đăng ký không hợp lệ.',
    
    // Course availability errors
    'course_not_published' => 'Khóa học chưa được xuất bản.',
    'course_not_available' => 'Khóa học không khả dụng để đăng ký.',
    'course_enrollment_closed' => 'Đăng ký khóa học đã đóng.',
    'course_capacity_full' => 'Khóa học đã đạt sức chứa tối đa.',
    'course_prerequisites_not_met' => 'Chưa đáp ứng điều kiện tiên quyết của khóa học.',
    'course_not_started' => 'Khóa học chưa bắt đầu.',
    'course_already_ended' => 'Khóa học đã kết thúc.',
    
    // User eligibility errors
    'user_not_active' => 'Tài khoản người dùng không hoạt động.',
    'user_suspended' => 'Tài khoản người dùng bị đình chỉ.',
    'user_not_eligible' => 'Người dùng không đủ điều kiện cho khóa học này.',
    'user_already_completed_course' => 'Người dùng đã hoàn thành khóa học này.',
    'user_has_pending_enrollment' => 'Người dùng có đăng ký đang chờ xử lý cho khóa học này.',
    
    // Payment and pricing errors
    'course_requires_payment' => 'Khóa học yêu cầu thanh toán để đăng ký.',
    'invalid_payment_method' => 'Phương thức thanh toán không hợp lệ.',
    'payment_failed' => 'Xử lý thanh toán thất bại.',
    'insufficient_funds' => 'Không đủ tiền để đăng ký.',
    'pricing_not_available' => 'Thông tin giá khóa học không khả dụng.',
    
    // Enrollment operation errors
    'enrollment_create_failed' => 'Không thể tạo đăng ký.',
    'enrollment_update_failed' => 'Không thể cập nhật đăng ký.',
    'enrollment_delete_failed' => 'Không thể xóa đăng ký.',
    'enrollment_activate_failed' => 'Không thể kích hoạt đăng ký.',
    'enrollment_deactivate_failed' => 'Không thể hủy kích hoạt đăng ký.',
    'enrollment_complete_failed' => 'Không thể hoàn thành đăng ký.',
    'enrollment_cancel_failed' => 'Không thể hủy đăng ký.',
    'enrollment_suspend_failed' => 'Không thể đình chỉ đăng ký.',
    'enrollment_resume_failed' => 'Không thể khôi phục đăng ký.',
    
    // Bulk operation errors
    'bulk_enrollment_failed' => 'Thao tác đăng ký hàng loạt thất bại.',
    'bulk_enrollment_partial_success' => 'Đăng ký hàng loạt hoàn thành với một số lỗi.',
    'invalid_bulk_data' => 'Dữ liệu không hợp lệ cho đăng ký hàng loạt.',
    'bulk_operation_not_permitted' => 'Thao tác hàng loạt không được phép.',
    'too_many_enrollments' => 'Quá nhiều đăng ký trong thao tác hàng loạt.',
    
    // Progress and completion errors
    'progress_update_failed' => 'Không thể cập nhật tiến độ đăng ký.',
    'invalid_progress_value' => 'Giá trị tiến độ không hợp lệ.',
    'progress_cannot_decrease' => 'Tiến độ không thể giảm.',
    'completion_requirements_not_met' => 'Chưa đáp ứng yêu cầu hoàn thành khóa học.',
    'certificate_generation_failed' => 'Không thể tạo chứng chỉ hoàn thành.',
    
    // Statistics and reporting errors
    'enrollment_statistics_not_available' => 'Thống kê đăng ký không khả dụng.',
    'insufficient_data_for_statistics' => 'Không đủ dữ liệu để tạo thống kê đăng ký.',
    'report_generation_failed' => 'Không thể tạo báo cáo đăng ký.',
    
    // Permission and access errors
    'enrollment_access_denied' => 'Không có quyền truy cập đăng ký.',
    'insufficient_permissions' => 'Không đủ quyền cho thao tác đăng ký.',
    'instructor_cannot_enroll' => 'Giảng viên không thể đăng ký khóa học của chính mình.',
    'admin_enrollment_required' => 'Yêu cầu phê duyệt của quản trị viên để đăng ký.',
    
    // Waitlist errors
    'waitlist_full' => 'Danh sách chờ của khóa học đã đầy.',
    'already_on_waitlist' => 'Người dùng đã có trong danh sách chờ của khóa học này.',
    'not_on_waitlist' => 'Người dùng không có trong danh sách chờ của khóa học này.',
    'waitlist_join_failed' => 'Không thể tham gia danh sách chờ.',
    'waitlist_remove_failed' => 'Không thể xóa khỏi danh sách chờ.',
    
    // Notification errors
    'enrollment_notification_failed' => 'Không thể gửi thông báo đăng ký.',
    'welcome_email_failed' => 'Không thể gửi email chào mừng.',
    'completion_notification_failed' => 'Không thể gửi thông báo hoàn thành.',
    
    // Data integrity errors
    'enrollment_data_corrupted' => 'Dữ liệu đăng ký bị hỏng.',
    'enrollment_history_missing' => 'Thiếu lịch sử đăng ký.',
    'duplicate_enrollment_detected' => 'Phát hiện đăng ký trùng lặp.',
    
    // Transfer and migration errors
    'enrollment_transfer_failed' => 'Không thể chuyển đăng ký.',
    'invalid_transfer_target' => 'Đích chuyển đăng ký không hợp lệ.',
    'transfer_not_permitted' => 'Chuyển đăng ký không được phép.',
    
    // Refund and cancellation errors
    'refund_not_available' => 'Hoàn tiền không khả dụng cho đăng ký này.',
    'refund_period_expired' => 'Thời hạn hoàn tiền đã hết.',
    'refund_processing_failed' => 'Xử lý hoàn tiền thất bại.',
    'cancellation_not_permitted' => 'Hủy đăng ký không được phép.',
    'cancellation_deadline_passed' => 'Đã quá hạn hủy đăng ký.',
];
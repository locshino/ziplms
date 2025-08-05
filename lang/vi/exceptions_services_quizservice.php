<?php

return [
    // Quiz not found errors
    'quiz_not_found' => 'Không tìm thấy bài kiểm tra.',
    'quiz_not_found_with_id' => 'Không tìm thấy bài kiểm tra với ID :id.',
    
    // Quiz validation errors
    'quiz_title_required' => 'Tiêu đề bài kiểm tra là bắt buộc.',
    'quiz_title_too_long' => 'Tiêu đề bài kiểm tra quá dài.',
    'quiz_description_required' => 'Mô tả bài kiểm tra là bắt buộc.',
    'quiz_time_limit_invalid' => 'Thời gian giới hạn phải là số dương.',
    'quiz_max_attempts_invalid' => 'Số lần thử tối đa phải là số dương.',
    'quiz_passing_score_invalid' => 'Điểm đạt phải từ 0 đến 100.',
    
    // Quiz status errors
    'quiz_not_published' => 'Bài kiểm tra chưa được xuất bản.',
    'quiz_already_published' => 'Bài kiểm tra đã được xuất bản.',
    'quiz_not_active' => 'Bài kiểm tra không hoạt động.',
    'quiz_expired' => 'Bài kiểm tra đã hết hạn.',
    'quiz_not_started' => 'Bài kiểm tra chưa bắt đầu.',
    
    // Quiz attempt errors
    'quiz_attempt_not_found' => 'Không tìm thấy lần làm bài.',
    'quiz_attempt_already_exists' => 'Người dùng đã có lần làm bài này.',
    'quiz_attempt_already_completed' => 'Lần làm bài đã hoàn thành.',
    'quiz_attempt_already_submitted' => 'Lần làm bài đã được nộp.',
    'quiz_attempt_time_expired' => 'Thời gian làm bài đã hết.',
    'quiz_max_attempts_reached' => 'Đã đạt số lần thử tối đa.',
    'quiz_attempt_not_started' => 'Lần làm bài chưa được bắt đầu.',
    'quiz_attempt_in_progress' => 'Lần làm bài đang trong quá trình thực hiện.',
    
    // Quiz permission errors
    'quiz_access_denied' => 'Không có quyền truy cập bài kiểm tra.',
    'quiz_not_enrolled' => 'Người dùng chưa đăng ký khóa học.',
    'quiz_prerequisites_not_met' => 'Chưa đáp ứng điều kiện tiên quyết.',
    'quiz_not_available_for_user' => 'Bài kiểm tra không khả dụng cho người dùng này.',
    
    // Quiz question errors
    'quiz_has_no_questions' => 'Bài kiểm tra không có câu hỏi.',
    'quiz_question_not_found' => 'Không tìm thấy câu hỏi.',
    'quiz_answer_required' => 'Câu trả lời là bắt buộc cho câu hỏi này.',
    'quiz_invalid_answer_format' => 'Định dạng câu trả lời không hợp lệ.',
    'quiz_answer_out_of_range' => 'Câu trả lời nằm ngoài phạm vi hợp lệ.',
    
    // Quiz operation errors
    'quiz_start_failed' => 'Không thể bắt đầu làm bài.',
    'quiz_submit_failed' => 'Không thể nộp bài.',
    'quiz_save_answer_failed' => 'Không thể lưu câu trả lời.',
    'quiz_calculate_score_failed' => 'Không thể tính điểm.',
    'quiz_generate_report_failed' => 'Không thể tạo báo cáo.',
    
    // Quiz grading errors
    'quiz_grading_failed' => 'Chấm điểm thất bại.',
    'quiz_auto_grading_not_available' => 'Chấm điểm tự động không khả dụng.',
    'quiz_manual_grading_required' => 'Yêu cầu chấm điểm thủ công.',
    'quiz_score_calculation_error' => 'Lỗi trong quá trình tính điểm.',
    
    // Quiz statistics errors
    'quiz_statistics_not_available' => 'Thống kê không khả dụng.',
    'quiz_insufficient_data_for_statistics' => 'Không đủ dữ liệu để tạo thống kê.',
    
    // Quiz configuration errors
    'quiz_invalid_configuration' => 'Cấu hình bài kiểm tra không hợp lệ.',
    'quiz_randomization_failed' => 'Không thể xáo trộn câu hỏi.',
    'quiz_time_limit_exceeded' => 'Thời gian giới hạn vượt quá tối đa hệ thống.',
    
    // Quiz deletion errors
    'quiz_cannot_delete_published' => 'Không thể xóa bài kiểm tra đã xuất bản.',
    'quiz_cannot_delete_with_attempts' => 'Không thể xóa bài kiểm tra có lần làm bài.',
    'quiz_delete_failed' => 'Không thể xóa bài kiểm tra.',
    
    // Quiz update errors
    'quiz_cannot_update_published' => 'Không thể cập nhật bài kiểm tra đã xuất bản có lần làm bài.',
    'quiz_update_failed' => 'Không thể cập nhật bài kiểm tra.',
    
    // Quiz creation errors
    'quiz_create_failed' => 'Không thể tạo bài kiểm tra.',
    'quiz_duplicate_title' => 'Đã tồn tại bài kiểm tra với tiêu đề này trong khóa học.',
    
    // Quiz course relationship errors
    'quiz_course_not_found' => 'Không tìm thấy khóa học của bài kiểm tra.',
    'quiz_course_not_active' => 'Khóa học của bài kiểm tra không hoạt động.',
    'quiz_not_belongs_to_course' => 'Bài kiểm tra không thuộc khóa học được chỉ định.',
];
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Quiz Repository Exception Language Lines (Vietnamese)
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the quiz repository exception
    | classes to provide localized error messages for quiz-related operations.
    |
    */

    'quiz_not_found' => 'Không tìm thấy bài kiểm tra.',
    'quiz_not_found_with_id' => 'Không tìm thấy bài kiểm tra với ID :id.',

    'quiz_not_published' => 'Bài kiểm tra chưa được xuất bản.',
    'quiz_not_published_with_id' => 'Bài kiểm tra với ID :id chưa được xuất bản.',

    'quiz_not_available' => 'Bài kiểm tra không khả dụng.',
    'quiz_not_available_with_id' => 'Bài kiểm tra với ID :id không khả dụng.',

    'quiz_not_started' => 'Bài kiểm tra chưa bắt đầu.',
    'quiz_not_started_with_id' => 'Bài kiểm tra với ID :id chưa bắt đầu.',

    'quiz_ended' => 'Bài kiểm tra đã kết thúc.',
    'quiz_ended_with_id' => 'Bài kiểm tra với ID :id đã kết thúc.',

    'attempt_not_found' => 'Không tìm thấy lần làm bài kiểm tra.',
    'attempt_not_found_with_id' => 'Không tìm thấy lần làm bài kiểm tra với ID :id.',

    'max_attempts_reached' => 'Đã đạt số lần làm bài tối đa.',
    'max_attempts_reached_with_details' => 'Đã đạt số lần làm bài tối đa (:max_attempts) cho bài kiểm tra :quiz_id.',

    'attempt_already_submitted' => 'Lần làm bài kiểm tra đã được nộp.',
    'attempt_already_submitted_with_id' => 'Lần làm bài kiểm tra với ID :attempt_id đã được nộp.',

    'attempt_not_submitted' => 'Lần làm bài kiểm tra chưa được nộp.',
    'attempt_not_submitted_with_id' => 'Lần làm bài kiểm tra với ID :attempt_id chưa được nộp.',

    'time_limit_exceeded' => 'Đã vượt quá thời gian làm bài.',
    'time_limit_exceeded_with_details' => 'Đã vượt quá thời gian làm bài (:time_limit phút) cho lần làm bài :attempt_id.',

    'attempt_start_failed' => 'Không thể bắt đầu lần làm bài kiểm tra.',
    'attempt_start_failed_with_reason' => 'Không thể bắt đầu lần làm bài kiểm tra: :reason',

    'attempt_submission_failed' => 'Không thể nộp bài kiểm tra.',
    'attempt_submission_failed_with_reason' => 'Không thể nộp bài kiểm tra: :reason',

    'statistics_calculation_failed' => 'Không thể tính toán thống kê bài kiểm tra.',
    'statistics_calculation_failed_with_reason' => 'Không thể tính toán thống kê bài kiểm tra: :reason',

    'insufficient_permissions' => 'Không đủ quyền để thực hiện thao tác này trên bài kiểm tra.',
    'insufficient_permissions_with_action' => 'Không đủ quyền để thực hiện thao tác bài kiểm tra: :action',
];

<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Badge Repository Exception Language Lines (Vietnamese)
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the badge repository exception
    | classes to provide localized error messages for badge-related operations.
    |
    */

    'badge_not_found' => 'Không tìm thấy huy hiệu.',
    'badge_not_found_with_id' => 'Không tìm thấy huy hiệu với ID :id.',
    
    'badge_not_active' => 'Huy hiệu không hoạt động.',
    'badge_not_active_with_id' => 'Huy hiệu với ID :id không hoạt động.',
    
    'badge_not_available' => 'Huy hiệu không khả dụng.',
    'badge_not_available_with_id' => 'Huy hiệu với ID :id không khả dụng.',
    
    'already_awarded' => 'Huy hiệu đã được trao cho người dùng này.',
    'already_awarded_with_details' => 'Huy hiệu :badge_id đã được trao cho người dùng :user_id.',
    
    'not_awarded' => 'Huy hiệu chưa được trao cho người dùng này.',
    'not_awarded_with_details' => 'Huy hiệu :badge_id chưa được trao cho người dùng :user_id.',
    
    'conditions_not_met' => 'Chưa đáp ứng điều kiện để nhận huy hiệu.',
    'conditions_not_met_with_details' => 'Chưa đáp ứng điều kiện huy hiệu :badge_id cho người dùng :user_id. Điều kiện thiếu: :missing_conditions',
    
    'condition_not_found' => 'Không tìm thấy điều kiện huy hiệu.',
    'condition_not_found_with_id' => 'Không tìm thấy điều kiện huy hiệu với ID :condition_id.',
    
    'invalid_condition_type' => 'Loại điều kiện huy hiệu không hợp lệ.',
    'invalid_condition_type_with_details' => 'Loại điều kiện huy hiệu không hợp lệ: :condition_type',
    
    'awarding_failed' => 'Không thể trao huy hiệu cho người dùng.',
    'awarding_failed_with_details' => 'Không thể trao huy hiệu :badge_id cho người dùng :user_id: :reason',
    
    'revocation_failed' => 'Không thể thu hồi huy hiệu từ người dùng.',
    'revocation_failed_with_details' => 'Không thể thu hồi huy hiệu :badge_id từ người dùng :user_id: :reason',
    
    'condition_evaluation_failed' => 'Không thể đánh giá điều kiện huy hiệu.',
    'condition_evaluation_failed_with_details' => 'Không thể đánh giá điều kiện huy hiệu :condition_id: :reason',
    
    'statistics_calculation_failed' => 'Không thể tính toán thống kê huy hiệu.',
    'statistics_calculation_failed_with_reason' => 'Không thể tính toán thống kê huy hiệu: :reason',
    
    'insufficient_permissions' => 'Không đủ quyền để thực hiện thao tác này trên huy hiệu.',
    'insufficient_permissions_with_action' => 'Không đủ quyền để thực hiện thao tác huy hiệu: :action',
];
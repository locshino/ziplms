<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Badge Repository Exception Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the badge repository exception
    | classes to provide localized error messages for badge-related operations.
    |
    */

    'badge_not_found' => 'Badge not found.',
    'badge_not_found_with_id' => 'Badge with ID :id not found.',
    
    'badge_not_active' => 'Badge is not active.',
    'badge_not_active_with_id' => 'Badge with ID :id is not active.',
    
    'badge_not_available' => 'Badge is not available.',
    'badge_not_available_with_id' => 'Badge with ID :id is not available.',
    
    'already_awarded' => 'Badge has already been awarded to this user.',
    'already_awarded_with_details' => 'Badge :badge_id has already been awarded to user :user_id.',
    
    'not_awarded' => 'Badge has not been awarded to this user.',
    'not_awarded_with_details' => 'Badge :badge_id has not been awarded to user :user_id.',
    
    'conditions_not_met' => 'Badge conditions are not met.',
    'conditions_not_met_with_details' => 'Badge :badge_id conditions are not met for user :user_id. Missing conditions: :missing_conditions',
    
    'condition_not_found' => 'Badge condition not found.',
    'condition_not_found_with_id' => 'Badge condition with ID :condition_id not found.',
    
    'invalid_condition_type' => 'Invalid badge condition type.',
    'invalid_condition_type_with_details' => 'Invalid badge condition type: :condition_type',
    
    'awarding_failed' => 'Failed to award badge to user.',
    'awarding_failed_with_details' => 'Failed to award badge :badge_id to user :user_id: :reason',
    
    'revocation_failed' => 'Failed to revoke badge from user.',
    'revocation_failed_with_details' => 'Failed to revoke badge :badge_id from user :user_id: :reason',
    
    'condition_evaluation_failed' => 'Failed to evaluate badge condition.',
    'condition_evaluation_failed_with_details' => 'Failed to evaluate badge condition :condition_id: :reason',
    
    'statistics_calculation_failed' => 'Failed to calculate badge statistics.',
    'statistics_calculation_failed_with_reason' => 'Failed to calculate badge statistics: :reason',
    
    'insufficient_permissions' => 'Insufficient permissions to perform this badge operation.',
    'insufficient_permissions_with_action' => 'Insufficient permissions to perform badge action: :action',
];
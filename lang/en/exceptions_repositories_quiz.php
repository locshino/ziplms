<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Quiz Repository Exception Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the quiz repository exception
    | classes to provide localized error messages for quiz-related operations.
    |
    */

    'quiz_not_found' => 'Quiz not found.',
    'quiz_not_found_with_id' => 'Quiz with ID :id not found.',
    
    'quiz_not_published' => 'Quiz is not published.',
    'quiz_not_published_with_id' => 'Quiz with ID :id is not published.',
    
    'quiz_not_available' => 'Quiz is not available.',
    'quiz_not_available_with_id' => 'Quiz with ID :id is not available.',
    
    'quiz_not_started' => 'Quiz has not started yet.',
    'quiz_not_started_with_id' => 'Quiz with ID :id has not started yet.',
    
    'quiz_ended' => 'Quiz has ended.',
    'quiz_ended_with_id' => 'Quiz with ID :id has ended.',
    
    'attempt_not_found' => 'Quiz attempt not found.',
    'attempt_not_found_with_id' => 'Quiz attempt with ID :id not found.',
    
    'max_attempts_reached' => 'Maximum number of quiz attempts reached.',
    'max_attempts_reached_with_details' => 'Maximum number of quiz attempts (:max_attempts) reached for quiz :quiz_id.',
    
    'attempt_already_submitted' => 'Quiz attempt has already been submitted.',
    'attempt_already_submitted_with_id' => 'Quiz attempt with ID :attempt_id has already been submitted.',
    
    'attempt_not_submitted' => 'Quiz attempt has not been submitted yet.',
    'attempt_not_submitted_with_id' => 'Quiz attempt with ID :attempt_id has not been submitted yet.',
    
    'time_limit_exceeded' => 'Quiz time limit has been exceeded.',
    'time_limit_exceeded_with_details' => 'Quiz time limit (:time_limit minutes) has been exceeded for attempt :attempt_id.',
    
    'attempt_start_failed' => 'Failed to start quiz attempt.',
    'attempt_start_failed_with_reason' => 'Failed to start quiz attempt: :reason',
    
    'attempt_submission_failed' => 'Failed to submit quiz attempt.',
    'attempt_submission_failed_with_reason' => 'Failed to submit quiz attempt: :reason',
    
    'statistics_calculation_failed' => 'Failed to calculate quiz statistics.',
    'statistics_calculation_failed_with_reason' => 'Failed to calculate quiz statistics: :reason',
    
    'insufficient_permissions' => 'Insufficient permissions to perform this quiz operation.',
    'insufficient_permissions_with_action' => 'Insufficient permissions to perform quiz action: :action',
];
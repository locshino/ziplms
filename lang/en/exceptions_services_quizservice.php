<?php

return [
    // Quiz not found errors
    'quiz_not_found' => 'Quiz not found.',
    'quiz_not_found_with_id' => 'Quiz with ID :id not found.',
    
    // Quiz validation errors
    'quiz_title_required' => 'Quiz title is required.',
    'quiz_title_too_long' => 'Quiz title is too long.',
    'quiz_description_required' => 'Quiz description is required.',
    'quiz_time_limit_invalid' => 'Quiz time limit must be a positive number.',
    'quiz_max_attempts_invalid' => 'Maximum attempts must be a positive number.',
    'quiz_passing_score_invalid' => 'Passing score must be between 0 and 100.',
    
    // Quiz status errors
    'quiz_not_published' => 'Quiz is not published.',
    'quiz_already_published' => 'Quiz is already published.',
    'quiz_not_active' => 'Quiz is not active.',
    'quiz_expired' => 'Quiz has expired.',
    'quiz_not_started' => 'Quiz has not started yet.',
    
    // Quiz attempt errors
    'quiz_attempt_not_found' => 'Quiz attempt not found.',
    'quiz_attempt_already_exists' => 'Quiz attempt already exists for this user.',
    'quiz_attempt_already_completed' => 'Quiz attempt is already completed.',
    'quiz_attempt_already_submitted' => 'Quiz attempt is already submitted.',
    'quiz_attempt_time_expired' => 'Quiz attempt time has expired.',
    'quiz_max_attempts_reached' => 'Maximum number of attempts reached.',
    'quiz_attempt_not_started' => 'Quiz attempt has not been started.',
    'quiz_attempt_in_progress' => 'Quiz attempt is already in progress.',
    
    // Quiz permission errors
    'quiz_access_denied' => 'Access to quiz is denied.',
    'quiz_not_enrolled' => 'User is not enrolled in the course.',
    'quiz_prerequisites_not_met' => 'Quiz prerequisites are not met.',
    'quiz_not_available_for_user' => 'Quiz is not available for this user.',
    
    // Quiz question errors
    'quiz_has_no_questions' => 'Quiz has no questions.',
    'quiz_question_not_found' => 'Quiz question not found.',
    'quiz_answer_required' => 'Answer is required for this question.',
    'quiz_invalid_answer_format' => 'Invalid answer format.',
    'quiz_answer_out_of_range' => 'Answer is out of valid range.',
    
    // Quiz operation errors
    'quiz_start_failed' => 'Failed to start quiz attempt.',
    'quiz_submit_failed' => 'Failed to submit quiz attempt.',
    'quiz_save_answer_failed' => 'Failed to save quiz answer.',
    'quiz_calculate_score_failed' => 'Failed to calculate quiz score.',
    'quiz_generate_report_failed' => 'Failed to generate quiz report.',
    
    // Quiz grading errors
    'quiz_grading_failed' => 'Quiz grading failed.',
    'quiz_auto_grading_not_available' => 'Auto grading is not available for this quiz.',
    'quiz_manual_grading_required' => 'Manual grading is required for this quiz.',
    'quiz_score_calculation_error' => 'Error occurred during score calculation.',
    
    // Quiz statistics errors
    'quiz_statistics_not_available' => 'Quiz statistics are not available.',
    'quiz_insufficient_data_for_statistics' => 'Insufficient data to generate statistics.',
    
    // Quiz configuration errors
    'quiz_invalid_configuration' => 'Quiz configuration is invalid.',
    'quiz_randomization_failed' => 'Failed to randomize quiz questions.',
    'quiz_time_limit_exceeded' => 'Quiz time limit configuration exceeded system maximum.',
    
    // Quiz deletion errors
    'quiz_cannot_delete_published' => 'Cannot delete a published quiz.',
    'quiz_cannot_delete_with_attempts' => 'Cannot delete quiz with existing attempts.',
    'quiz_delete_failed' => 'Failed to delete quiz.',
    
    // Quiz update errors
    'quiz_cannot_update_published' => 'Cannot update a published quiz with active attempts.',
    'quiz_update_failed' => 'Failed to update quiz.',
    
    // Quiz creation errors
    'quiz_create_failed' => 'Failed to create quiz.',
    'quiz_duplicate_title' => 'A quiz with this title already exists in the course.',
    
    // Quiz course relationship errors
    'quiz_course_not_found' => 'Course for this quiz not found.',
    'quiz_course_not_active' => 'Course for this quiz is not active.',
    'quiz_not_belongs_to_course' => 'Quiz does not belong to the specified course.',
];
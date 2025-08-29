<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Assignment Repository Exception Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the assignment repository exception
    | classes to provide localized error messages for assignment-related operations.
    |
    */

    'assignment_not_found' => 'Assignment not found.',
    'assignment_not_found_with_id' => 'Assignment with ID :id not found.',

    'assignment_not_published' => 'Assignment is not published.',
    'assignment_not_published_with_id' => 'Assignment with ID :id is not published.',

    'assignment_not_available' => 'Assignment is not available.',
    'assignment_not_available_with_id' => 'Assignment with ID :id is not available.',

    'assignment_not_started' => 'Assignment has not started yet.',
    'assignment_not_started_with_id' => 'Assignment with ID :id has not started yet.',

    'assignment_ended' => 'Assignment has ended.',
    'assignment_ended_with_id' => 'Assignment with ID :id has ended.',

    'submission_not_found' => 'Assignment submission not found.',
    'submission_not_found_with_id' => 'Assignment submission with ID :id not found.',

    'submission_already_exists' => 'Assignment submission already exists.',
    'submission_already_exists_with_details' => 'Assignment submission already exists for assignment :assignment_id and student :student_id.',

    'submission_already_graded' => 'Assignment submission has already been graded.',
    'submission_already_graded_with_id' => 'Assignment submission with ID :submission_id has already been graded.',

    'submission_not_graded' => 'Assignment submission has not been graded yet.',
    'submission_not_graded_with_id' => 'Assignment submission with ID :submission_id has not been graded yet.',

    'late_submission' => 'Assignment submission is late.',
    'late_submission_with_id' => 'Assignment submission is late for assignment :assignment_id.',

    'file_upload_failed' => 'Failed to upload assignment file.',
    'file_upload_failed_with_reason' => 'Failed to upload assignment file: :reason',

    'grading_failed' => 'Failed to grade assignment submission.',
    'grading_failed_with_reason' => 'Failed to grade assignment submission: :reason',

    'statistics_calculation_failed' => 'Failed to calculate assignment statistics.',
    'statistics_calculation_failed_with_reason' => 'Failed to calculate assignment statistics: :reason',

    'insufficient_permissions' => 'Insufficient permissions to perform this assignment operation.',
    'insufficient_permissions_with_action' => 'Insufficient permissions to perform assignment action: :action',
];

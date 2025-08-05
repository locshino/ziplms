<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User Repository Exception Messages
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for user repository specific
    | exception messages that are thrown by UserRepository class.
    |
    */

    'user_not_found' => 'User not found.',
    'user_not_found_with_id' => 'User with ID :id not found.',
    'user_not_found_with_email' => 'User with email :email not found.',
    'email_already_exists' => 'Email address already exists.',
    'email_already_taken' => 'The email :email is already taken.',
    'invalid_email_format' => 'Invalid email format provided.',
    'password_too_weak' => 'Password does not meet security requirements.',
    'invalid_role' => 'Invalid role specified.',
    'role_not_found' => 'Role :role not found.',
    'user_already_has_role' => 'User already has the specified role.',
    'user_does_not_have_role' => 'User does not have the specified role.',
    'cannot_delete_admin' => 'Cannot delete admin user.',
    'cannot_modify_own_role' => 'Cannot modify your own role.',
    'user_is_inactive' => 'User account is inactive.',
    'user_is_suspended' => 'User account is suspended.',
    'invalid_user_status' => 'Invalid user status provided.',
    'create_user_failed' => 'Failed to create user account.',
    'update_user_failed' => 'Failed to update user information.',
    'delete_user_failed' => 'Failed to delete user account.',
    'user_has_active_enrollments' => 'Cannot delete user with active course enrollments.',
    'user_has_pending_assignments' => 'Cannot delete user with pending assignments.',
    'instructor_has_active_courses' => 'Cannot delete instructor with active courses.',
];
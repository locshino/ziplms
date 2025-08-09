# Noun-Specific Permission Guide

This guide provides detailed examples of how to use verbs and contexts specific to each noun in the permission system `{verb}_{noun}_{context}[::attribute]`.

## Quiz-Specific Permissions

### Core Quiz Operations
```php
// Taking quizzes - the main student action
'take_quiz_student' // Students can take quizzes
'take_quiz_enrolled' // Only enrolled students can take
'take_quiz_timed' // Take timed quizzes
'take_quiz_unlimited' // Take with unlimited attempts

// Quiz attempt management
'start_quiz_student' // Start a quiz attempt
'pause_quiz_timed' // Pause during timed quiz
'resume_quiz_timed' // Resume paused quiz
'finish_quiz_in_progress' // Finish ongoing quiz
'retake_quiz_limited_attempts' // Retake with attempt limits

// Quiz administration
'create_quiz_teacher' // Teachers create quizzes
'publish_quiz_draft' // Publish draft quizzes
'grade_quiz_manual_graded' // Grade manually graded quizzes
'review_quiz_randomized' // Review randomized questions
```

### Quiz Context Examples
```php
// Attempt-based contexts
'take_quiz_unlimited' // No attempt limit
'take_quiz_limited_attempts' // Has attempt limit
'retake_quiz_unlimited' // Can retake unlimited times

// Time-based contexts
'start_quiz_timed' // Timed quiz
'pause_quiz_timed' // Can pause timed quiz
'take_quiz_sequential' // Questions in sequence
'take_quiz_randomized' // Random question order
```

## Assignment-Specific Permissions

### Assignment Lifecycle
```php
// Creation and distribution
'create_assignment_teacher' // Teachers create assignments
'distribute_assignment_group' // Distribute to groups
'distribute_assignment_individual' // Individual assignments

// Student interactions
'submit_assignment_student' // Students submit work
'submit_assignment_group' // Group submissions
'submit_assignment_individual' // Individual submissions

// Teacher management
'collect_assignment_teacher' // Collect submissions
'grade_assignment_manual_graded' // Manual grading
'grade_assignment_auto_graded' // Auto-graded assignments
'extend_assignment_teacher' // Extend deadlines

// Peer review process
'review_assignment_peer_review' // Peer review assignments
'assign_assignment_peer_review' // Assign peer reviewers
```

### Assignment Context Examples
```php
// Assignment types
'create_assignment_group' // Group assignments
'create_assignment_individual' // Individual work
'submit_assignment_peer_review' // Peer review submissions

// Grading contexts
'grade_assignment_auto_graded' // Automatically graded
'grade_assignment_manual_graded' // Requires manual grading

// Timing contexts
'submit_assignment_on_time' // On-time submissions
'accept_assignment_late' // Late submission acceptance
'collect_assignment_early' // Early collection
```

## Course-Specific Permissions

### Course Participation
```php
// Student actions
'enroll_course_public' // Enroll in public courses
'attend_course_enrolled' // Attend enrolled courses
'complete_course_student' // Complete course requirements
'withdraw_course_enrolled' // Withdraw from course

// Teacher actions
'teach_course_assigned' // Teach assigned courses
'manage_course_owner' // Manage owned courses
'transfer_course_manager' // Transfer course ownership

// Administrative actions
'approve_course_prerequisite' // Approve prerequisite courses
'publish_course_draft' // Publish draft courses
'archive_course_completed' // Archive completed courses
```

### Course Context Examples
```php
// Course types
'enroll_course_elective' // Elective courses
'enroll_course_mandatory' // Required courses
'view_course_prerequisite' // Prerequisite courses

// Delivery methods
'attend_course_online' // Online courses
'attend_course_offline' // In-person courses
'attend_course_hybrid' // Mixed delivery

// Status contexts
'view_course_active' // Currently active
'archive_course_completed' // Completed courses
'restore_course_archived' // Restore from archive
```

## User-Specific Permissions

### User Management
```php
// User lifecycle
'invite_user_department' // Invite to department
'activate_user_verified' // Activate verified users
'suspend_user_admin' // Suspend users (admin only)
'deactivate_user_inactive' // Deactivate inactive users

// User verification
'verify_user_unverified' // Verify new users
'approve_user_pending' // Approve pending users
'reject_user_unverified' // Reject unverified users

// Role management
'assign_user_teacher::role' // Assign teacher role
'revoke_user_suspended::role' // Revoke roles from suspended users
```

### User Context Examples
```php
// User status
'manage_user_verified' // Verified users only
'activate_user_unverified' // Unverified users
'suspend_user_active' // Currently active users

// User types
'invite_user_first_time' // First-time users
'manage_user_returning' // Returning users
'approve_user_guest' // Guest user approval
```

## Badge-Specific Permissions

### Badge System
```php
// Badge creation and management
'create_badge_admin' // Admin creates badges
'award_badge_excellence' // Award excellence badges
'revoke_badge_admin' // Revoke badges (admin only)

// Badge earning
'earn_badge_achievement' // Earn achievement badges
'earn_badge_participation' // Participation badges
'earn_badge_completion' // Course completion badges

// Badge tracking
'track_badge_participation' // Track participation
'certify_badge_completion' // Certify completion
'evaluate_badge_achievement' // Evaluate achievements
```

### Badge Context Examples
```php
// Badge types
'award_badge_achievement' // Achievement-based
'earn_badge_participation' // Participation-based
'certify_badge_completion' // Completion certificates
'track_badge_excellence' // Excellence tracking

// Badge status
'view_badge_active' // Currently active badges
'revoke_badge_inactive' // Revoke inactive badges
'restore_badge_archived' // Restore archived badges
```

## Media-Specific Permissions

### Media Management
```php
// Media upload and management
'upload_media_teacher' // Teachers upload content
'manage_media_owner' // Manage owned media
'delete_media_inactive' // Delete inactive media

// Media consumption
'download_media_enrolled' // Download in enrolled courses
'stream_media_video' // Stream video content
'view_media_interactive' // View interactive content

// Media types
'upload_media_document' // Document uploads
'stream_media_audio' // Audio streaming
'view_media_image' // Image viewing
```

## Implementation Examples

### In Policy Classes
```php
class QuizPolicy
{
    public function take(User $user, Quiz $quiz): bool
    {
        // Check if user can take quiz as student
        return $user->hasPermission('take_quiz_student') 
            && $user->isEnrolledIn($quiz->course);
    }
    
    public function retake(User $user, Quiz $quiz): bool
    {
        // Check retake permissions based on attempt limits
        if ($quiz->unlimited_attempts) {
            return $user->hasPermission('retake_quiz_unlimited');
        }
        
        return $user->hasPermission('retake_quiz_limited_attempts')
            && $user->getRemainingAttempts($quiz) > 0;
    }
}
```

### In Controllers
```php
class QuizController
{
    public function take(Quiz $quiz)
    {
        // Check specific quiz-taking permission
        $this->authorize('take', $quiz);
        
        // Additional context-based checks
        if ($quiz->is_timed && !auth()->user()->hasPermission('take_quiz_timed')) {
            abort(403, 'Cannot take timed quizzes');
        }
        
        return view('quiz.take', compact('quiz'));
    }
}
```

### Permission Seeding
```php
// In database seeder
$permissions = [
    // Quiz permissions
    'take_quiz_student',
    'take_quiz_timed',
    'retake_quiz_unlimited',
    'start_quiz_student',
    'finish_quiz_in_progress',
    
    // Assignment permissions
    'submit_assignment_student',
    'submit_assignment_group',
    'review_assignment_peer_review',
    
    // Course permissions
    'enroll_course_public',
    'attend_course_enrolled',
    'complete_course_student',
];

foreach ($permissions as $permission) {
    Permission::create(['name' => $permission]);
}
```

This guide helps developers understand how to create specific, contextual permissions that align with the actual workflows and requirements of each entity type in the LMS system.
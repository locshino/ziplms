# Permission System Documentation

## Overview

This permission system follows the pattern: `{verb}_{noun}_{context}[::attribute]`

- **Verb**: Action to be performed (CREATE, VIEW, UPDATE, DELETE, etc.)
- **Noun**: Entity being acted upon (USER, COURSE, QUIZ, etc.)
- **Context**: Scope or condition of the permission (ALL, OWNER, ADMIN, etc.)
- **Attribute**: Optional specific attribute or field

## Role-Based Permission Examples

### Admin Role
- `manage_system_all` - Full system management
- `configure_settings_all` - Configure all system settings
- `view_reports_all` - View all reports
- `backup_system_all` - Create system backups
- `manage_user_all` - Manage all users
- `assign_role_all` - Assign roles to any user

### Manager Role
- `manage_course_department` - Manage courses within department
- `view_analytics_department` - View department analytics
- `approve_course_assigned` - Approve assigned courses
- `monitor_user_supervised` - Monitor supervised users
- `export_reports_department` - Export department reports
- `assign_teacher_department` - Assign teachers within department

### Teacher Role
- `create_course_owner` - Create own courses
- `update_course_assigned` - Update assigned courses
- `teach_course_assigned` - Teach assigned courses
- `grade_submission_assigned` - Grade submissions for assigned courses
- `view_quiz_attempt_assigned` - View quiz attempts for assigned courses
- `publish_course_owner` - Publish own courses
- `review_assignment_assigned` - Review assignments for assigned courses
- `notify_student_enrolled` - Notify enrolled students
- `track_student_enrolled` - Track enrolled student progress

### Student Role
- `view_course_enrolled` - View enrolled courses
- `attempt_quiz_enrolled` - Attempt quizzes in enrolled courses
- `submit_assignment_enrolled` - Submit assignments for enrolled courses
- `view_results_self` - View own results
- `enroll_course_public` - Enroll in public courses
- `view_badge_self` - View own badges
- `update_user_self` - Update own profile

## Noun-Specific Permission Examples

### USER Permissions
```php
// Admin permissions
'create_user_all'
'view_user_all'
'update_user_all'
'delete_user_all'
'reset_user_all' // Reset passwords
'monitor_user_all'
'invite_user_department' // Invite users to department
'suspend_user_admin' // Suspend users (admin only)
'activate_user_verified' // Activate verified users
'deactivate_user_inactive' // Deactivate inactive users

// Manager permissions
'view_user_department'
'update_user_supervised'
'assign_user_department'

// Teacher permissions
'view_user_enrolled' // View enrolled students
'message_user_enrolled' // Message enrolled students

// Student permissions
'view_user_self'
'update_user_self'
```

### COURSE Permissions
```php
// Admin permissions
'manage_course_all'
'archive_course_all'
'restore_course_all'

// Manager permissions
'approve_course_department'
'publish_course_department'
'assign_course_department'
'transfer_course_manager' // Transfer between courses

// Teacher permissions
'create_course_owner'
'update_course_assigned'
'teach_course_assigned'
'publish_course_owner'
'archive_course_owner'

// Student permissions
'view_course_enrolled'
'view_course_public'
'enroll_course_public'
'attend_course_enrolled' // Attend enrolled courses
'complete_course_student' // Complete courses as student
'withdraw_course_enrolled' // Withdraw from enrolled courses
```

### QUIZ Permissions
```php
// Teacher permissions
'create_quiz_assigned' // Create quiz for assigned courses
'update_quiz_owner'
'delete_quiz_owner'
'publish_quiz_assigned'
'view_results_assigned'
'grade_quiz_teacher' // Teachers grade quizzes
'review_quiz_randomized' // Review randomized quiz questions

// Student permissions
'take_quiz_student' // **Students take quizzes** (main example)
'attempt_quiz_enrolled'
'view_quiz_enrolled'
'view_results_self'
'retake_quiz_unlimited' // Retake quizzes with unlimited attempts
'start_quiz_timed' // Start timed quizzes
'finish_quiz_in_progress' // Finish ongoing quizzes
'pause_quiz_timed' // Pause timed quizzes
'resume_quiz_paused' // Resume paused quizzes
'attempt_quiz_limited_attempts' // Attempt quizzes with limited tries
```

### ASSIGNMENT Permissions
```php
// Teacher permissions
'create_assignment_assigned'
'update_assignment_owner'
'grade_assignment_assigned'
'review_assignment_assigned'
'distribute_assignment_group' // Distribute group assignments
'collect_assignment_teacher' // Teachers collect submissions
'extend_assignment_teacher' // Extend assignment deadlines
'grade_assignment_manual_graded' // Grade manually graded assignments
'review_assignment_peer_review' // Peer review assignments

// Student permissions
'view_assignment_enrolled'
'submit_assignment_enrolled'
'view_results_self'
```

### SUBMISSION Permissions
```php
// Teacher permissions
'view_submission_assigned'
'grade_submission_assigned'
'review_submission_assigned'
'approve_submission_assigned'
'reject_submission_assigned'
'collect_submission_on_time' // Collect on-time submissions
'accept_submission_late' // Accept late submissions
'reject_submission_early' // Reject early submissions
'track_submission_resubmission' // Track resubmissions

// Student permissions
'create_submission_enrolled'
'view_submission_self'
'update_submission_self' // Before deadline
```

### ENROLLMENT Permissions
```php
// Admin permissions
'manage_enrollment_all'
'view_enrollment_all'

// Manager permissions
'view_enrollment_department'
'approve_enrollment_department'
'transfer_enrollment_manager' // Transfer enrollments
'approve_enrollment_prerequisite' // Approve prerequisite enrollments

// Teacher permissions
'view_enrollment_assigned'
'approve_enrollment_assigned'

// Student permissions
'create_enrollment_public' // Self-enroll in public courses
'view_enrollment_self'
```

### BADGE Permissions
```php
// Admin permissions
'create_badge_all'
'assign_badge_all'
'manage_badge_all'
'revoke_badge_admin' // Revoke badges (admin only)

// Manager permissions
'create_badge_department'
'assign_badge_supervised'
'award_badge_excellence' // Award excellence badges

// Teacher permissions
'assign_badge_enrolled' // Assign to enrolled students
'view_badge_assigned'
'track_badge_participation' // Track participation badges
'certify_badge_completion' // Certify completion badges

// Student permissions
'view_badge_self'
'view_badge_public'
'earn_badge_achievement' // Earn achievement badges
```

### MEDIA Permissions
```php
// Teacher permissions
'upload_media_teacher' // Teachers upload media
'manage_media_document' // Manage document files
'delete_media_owner' // Delete own media files

// Student permissions
'download_media_enrolled' // Download media in enrolled courses
'stream_media_video' // Stream video content
'view_media_interactive' // View interactive media
```

### REPORTS & ANALYTICS Permissions
```php
// Admin permissions
'view_reports_all'
'export_reports_all'
'view_analytics_all'
'export_analytics_admin' // Export analytics (admin only)
'schedule_reports_automated' // Schedule automated reports
'monitor_analytics_real_time' // Monitor real-time analytics

// Manager permissions
'view_reports_department'
'export_reports_department'
'view_analytics_supervised'
'generate_reports_manager' // Generate reports as manager
'analyze_reports_current' // Analyze current period reports

// Teacher permissions
'view_reports_assigned'
'export_reports_assigned'
'track_student_enrolled'
'track_analytics_teacher' // Track student analytics
```

## Context Usage Guidelines

### Relationship-Based Contexts
- `all`: Full access to all entities
- `owner`: Access to owned entities
- `self`: Access to own data only
- `enrolled`: Access related to enrollment
- `assigned`: Access to assigned entities
- `supervised`: Access to supervised entities

### Role-Based Contexts
- `admin`: Admin-level access
- `manager`: Manager-level access
- `teacher`: Teacher-level access
- `student`: Student-level access

### Status-Based Contexts
- `active`: Only active entities
- `published`: Only published content
- `draft`: Only draft content
- `pending`: Only pending items
- `completed`: Only completed items

### Time-Based Contexts
- `current`: Current semester/period
- `past`: Historical data
- `future`: Future scheduled items

## Implementation Examples

### In Policy Classes
```php
class CoursePolicy
{
    public function view(User $user, Course $course): bool
    {
        // Check if user has permission to view this specific course
        return $user->can('view_course_enrolled') && $user->isEnrolledIn($course)
            || $user->can('view_course_assigned') && $user->isAssignedTo($course)
            || $user->can('view_course_all');
    }
    
    public function teach(User $user, Course $course): bool
    {
        return $user->can('teach_course_assigned') && $user->isAssignedTo($course);
    }
}
```

### In Controllers/Resources
```php
class CourseController
{
    public function index()
    {
        $query = Course::query();
        
        if (auth()->user()->can('view_course_all')) {
            // No restrictions
        } elseif (auth()->user()->can('view_course_assigned')) {
            $query->whereHas('teachers', fn($q) => $q->where('user_id', auth()->id()));
        } elseif (auth()->user()->can('view_course_enrolled')) {
            $query->whereHas('enrollments', fn($q) => $q->where('user_id', auth()->id()));
        }
        
        return $query->get();
    }
}
```

This system provides fine-grained control over permissions while maintaining clarity and consistency across the application.
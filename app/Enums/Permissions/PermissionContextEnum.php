<?php

namespace App\Enums\Permissions;

use App\Enums\Concerns\HasOptions;

enum PermissionContextEnum: string
{
    use HasOptions;

    // Relationship-Based
    case ALL = 'all';
    case OWNER = 'owner';
    case PUBLIC = 'public';
    case AUTHENTICATION = 'authentication';
    case SELF = 'self';
    
    // Role-Based contexts
    case ADMIN = 'admin'; // Admin-level access
    case MANAGER = 'manager'; // Manager-level access
    case TEACHER = 'teacher'; // Teacher-level access
    case STUDENT = 'student'; // Student-level access
    
    // Educational contexts
    case ENROLLED = 'enrolled'; // For enrolled students
    case ASSIGNED = 'assigned'; // For assigned teachers/content
    case SUPERVISED = 'supervised'; // For supervised entities
    case DEPARTMENT = 'department'; // Department-level access
    case INSTITUTION = 'institution'; // Institution-level access
    
    // Status-Based contexts
    case ACTIVE = 'active'; // For active entities
    case INACTIVE = 'inactive'; // For inactive entities
    case PENDING = 'pending'; // For pending approval
    case COMPLETED = 'completed'; // For completed items
    case IN_PROGRESS = 'in_progress'; // For items in progress
    case DRAFT = 'draft'; // For draft content
    case PUBLISHED = 'published'; // For published content
    case ARCHIVED = 'archived'; // For archived content
    
    // Time-Based contexts
    case CURRENT = 'current'; // Current semester/period
    case PAST = 'past'; // Past records
    case FUTURE = 'future'; // Future scheduled items
    
    // Attribute-Based
    case TAG = 'tag';
    case ID = 'id';
    case GRADE = 'grade'; // Grade-based access
    case LEVEL = 'level'; // Level-based access
    
    // Quiz-specific contexts
    case TIMED = 'timed'; // For timed quizzes
    case UNLIMITED = 'unlimited'; // For unlimited attempts
    case LIMITED_ATTEMPTS = 'limited_attempts'; // For limited attempts
    case RANDOMIZED = 'randomized'; // For randomized questions
    case SEQUENTIAL = 'sequential'; // For sequential questions
    
    // Assignment-specific contexts
    case GROUP = 'group'; // For group assignments
    case INDIVIDUAL = 'individual'; // For individual assignments
    case PEER_REVIEW = 'peer_review'; // For peer review assignments
    case AUTO_GRADED = 'auto_graded'; // For auto-graded assignments
    case MANUAL_GRADED = 'manual_graded'; // For manually graded assignments
    
    // Course-specific contexts
    case PREREQUISITE = 'prerequisite'; // For prerequisite courses
    case ELECTIVE = 'elective'; // For elective courses
    case MANDATORY = 'mandatory'; // For mandatory courses
    case ONLINE = 'online'; // For online courses
    case OFFLINE = 'offline'; // For offline courses
    case HYBRID = 'hybrid'; // For hybrid courses
    
    // User-specific contexts
    case VERIFIED = 'verified'; // For verified users
    case UNVERIFIED = 'unverified'; // For unverified users
    case FIRST_TIME = 'first_time'; // For first-time users
    case RETURNING = 'returning'; // For returning users
    case GUEST = 'guest'; // For guest users
    
    // Submission-specific contexts
    case ON_TIME = 'on_time'; // For on-time submissions
    case LATE = 'late'; // For late submissions
    case EARLY = 'early'; // For early submissions
    case RESUBMISSION = 'resubmission'; // For resubmissions
    
    // Badge-specific contexts
    case ACHIEVEMENT = 'achievement'; // For achievement badges
    case PARTICIPATION = 'participation'; // For participation badges
    case COMPLETION = 'completion'; // For completion badges
    case EXCELLENCE = 'excellence'; // For excellence badges
    
    // Media-specific contexts
    case VIDEO = 'video'; // For video content
    case AUDIO = 'audio'; // For audio content
    case DOCUMENT = 'document'; // For document files
    case IMAGE = 'image'; // For image files
    case INTERACTIVE = 'interactive'; // For interactive content

    public static function getRelationshipContexts(): array
    {
        return [
            self::ALL->value,
            self::OWNER->value,
            self::PUBLIC->value,
            self::AUTHENTICATION->value,
            self::SELF->value,
            self::ENROLLED->value,
            self::ASSIGNED->value,
            self::SUPERVISED->value,
        ];
    }

    public static function getRoleBasedContexts(): array
    {
        return [
            self::ADMIN->value,
            self::MANAGER->value,
            self::TEACHER->value,
            self::STUDENT->value,
        ];
    }

    public static function getStatusBasedContexts(): array
    {
        return [
            self::ACTIVE->value,
            self::INACTIVE->value,
            self::PENDING->value,
            self::COMPLETED->value,
            self::IN_PROGRESS->value,
            self::DRAFT->value,
            self::PUBLISHED->value,
            self::ARCHIVED->value,
        ];
    }

    public static function getTimeBasedContexts(): array
    {
        return [
            self::CURRENT->value,
            self::PAST->value,
            self::FUTURE->value,
        ];
    }

    public static function getEducationalContexts(): array
    {
        return [
            self::DEPARTMENT->value,
            self::INSTITUTION->value,
        ];
    }

    public static function getAttributeContexts(): array
    {
        return [
            self::TAG->value,
            self::ID->value,
            self::GRADE->value,
            self::LEVEL->value,
        ];
    }
}

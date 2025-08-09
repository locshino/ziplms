<?php

namespace App\Enums\Permissions;

use App\Enums\Concerns\HasOptions;

enum PermissionVerbEnum: string
{
    use HasOptions;

    // Basic CRUD operations
    case CREATE = 'create';
    case VIEW = 'view';
    case UPDATE = 'update';
    case DELETE = 'delete';

    // Special permissions
    case ASSIGN = 'assign';
    case MANAGE = 'manage';
    case VIEW_LIST = 'view_list';
    case GRADE = 'grade';
    case ATTEMPT = 'attempt';
    case SUBMIT = 'submit';
    case VIEW_RESULTS = 'view_results';
    
    // Educational operations
    case ENROLL = 'enroll'; // For course enrollment
    case TEACH = 'teach'; // For teaching courses
    case REVIEW = 'review'; // For reviewing submissions/assignments
    case APPROVE = 'approve'; // For approving content/requests
    case REJECT = 'reject'; // For rejecting submissions/requests
    case PUBLISH = 'publish'; // For publishing courses/content
    case UNPUBLISH = 'unpublish'; // For unpublishing content
    case ARCHIVE = 'archive'; // For archiving old content
    case RESTORE = 'restore'; // For restoring archived content
    
    // Administrative operations
    case CONFIGURE = 'configure'; // For system configuration
    case MONITOR = 'monitor'; // For monitoring system/users
    case EXPORT = 'export'; // For exporting data/reports
    case IMPORT = 'import'; // For importing data
    case BACKUP = 'backup'; // For creating backups
    case RESET = 'reset'; // For resetting passwords/data
    
    // Communication operations
    case NOTIFY = 'notify'; // For sending notifications
    case MESSAGE = 'message'; // For messaging users
    case ANNOUNCE = 'announce'; // For making announcements
    
    // Progress tracking
    case TRACK = 'track'; // For tracking progress
    case EVALUATE = 'evaluate'; // For evaluating performance
    case CERTIFY = 'certify'; // For issuing certificates
    
    // Quiz-specific operations
    case TAKE = 'take'; // For taking quizzes/tests
    case RETAKE = 'retake'; // For retaking quizzes
    case START = 'start'; // For starting quizzes/assignments
    case FINISH = 'finish'; // For finishing quizzes/assignments
    case PAUSE = 'pause'; // For pausing ongoing activities
    case RESUME = 'resume'; // For resuming paused activities
    
    // Assignment-specific operations
    case DISTRIBUTE = 'distribute'; // For distributing assignments
    case COLLECT = 'collect'; // For collecting submissions
    case EXTEND = 'extend'; // For extending deadlines
    
    // Course-specific operations
    case ATTEND = 'attend'; // For attending courses/classes
    case COMPLETE = 'complete'; // For completing courses
    case WITHDRAW = 'withdraw'; // For withdrawing from courses
    case TRANSFER = 'transfer'; // For transferring between courses
    
    // User-specific operations
    case INVITE = 'invite'; // For inviting users
    case SUSPEND = 'suspend'; // For suspending users
    case ACTIVATE = 'activate'; // For activating users
    case DEACTIVATE = 'deactivate'; // For deactivating users
    
    // Badge-specific operations
    case EARN = 'earn'; // For earning badges
    case AWARD = 'award'; // For awarding badges
    case REVOKE = 'revoke'; // For revoking badges/permissions
    
    // Media-specific operations
    case UPLOAD = 'upload'; // For uploading media
    case DOWNLOAD = 'download'; // For downloading files
    case STREAM = 'stream'; // For streaming media content
    
    // Report-specific operations
    case GENERATE = 'generate'; // For generating reports
    case SCHEDULE = 'schedule'; // For scheduling tasks/reports
    case ANALYZE = 'analyze'; // For analyzing data
}

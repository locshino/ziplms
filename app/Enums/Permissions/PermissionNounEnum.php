<?php

namespace App\Enums\Permissions;

use App\Enums\Concerns\HasOptions;

enum PermissionNounEnum: string
{
    use HasOptions;

    // Single entities based on actual models
    case USER = 'user';
    case ROLE = 'role';
    case PERMISSION = 'permission';
    case COURSE = 'course';
    case QUIZ = 'quiz';
    case ASSIGNMENT = 'assignment';
    case ENROLLMENT = 'enrollment';
    case BADGE = 'badge';
    case QUESTION = 'question';
    case SUBMISSION = 'submission';
    case TAG = 'tag';
    case MEDIA = 'media';
    case ANSWER_CHOICE = 'answer_choice';
    case QUIZ_ATTEMPT = 'quiz_attempt';
    case STUDENT_QUIZ_ANSWER = 'student_quiz_answer';
    case USER_BADGE = 'user_badge';
    case BADGE_CONDITION = 'badge_condition';

    // System and administrative contexts
    case SYSTEM = 'system';
    case SETTINGS = 'settings';
    case REPORTS = 'reports';
    case ANALYTICS = 'analytics';
    case NOTIFICATIONS = 'notifications';
    case BACKUPS = 'backups';

    /**
     * Get the model class name associated with the permission noun.
     *
     * This method maps each permission noun to its corresponding model class,
     * allowing for dynamic model retrieval based on the permission context.
     *
     * @return string The fully qualified class name of the associated model
     */
    public function getModels(): string
    {
        return match ($this) {
            self::USER => \App\Models\User::class,
            self::ROLE => \App\Models\Role::class,
            self::PERMISSION => \App\Models\Permission::class,
            self::COURSE => \App\Models\Course::class,
            self::QUIZ => \App\Models\Quiz::class,
            self::ASSIGNMENT => \App\Models\Assignment::class,
            self::ENROLLMENT => \App\Models\Enrollment::class,
            self::BADGE => \App\Models\Badge::class,
            self::QUESTION => \App\Models\Question::class,
            self::SUBMISSION => \App\Models\Submission::class,
            self::TAG => \App\Models\Tag::class,
            self::MEDIA => \App\Models\Media::class,
            self::ANSWER_CHOICE => \App\Models\AnswerChoice::class,
            self::QUIZ_ATTEMPT => \App\Models\QuizAttempt::class,
            self::STUDENT_QUIZ_ANSWER => \App\Models\StudentQuizAnswer::class,
            self::USER_BADGE => \App\Models\UserBadge::class,
            self::BADGE_CONDITION => \App\Models\BadgeCondition::class,
        };
    }
}

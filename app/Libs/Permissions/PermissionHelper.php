<?php

namespace App\Libs\Permissions;

use App\Enums\Permissions\PermissionContextEnum;
use App\Enums\Permissions\PermissionNounEnum;
use App\Enums\Permissions\PermissionVerbEnum;
use InvalidArgumentException;

/**
 * PermissionHelper
 *
 * A helper class that extends PermissionBuilder with fluent interface methods
 * for creating permission strings with convenient helper methods.
 */
class PermissionHelper extends PermissionBuilder
{
    /**
     * Create a new PermissionHelper instance.
     *
     * This helper provides a fluent interface for building permission strings
     * following the verb-noun-context pattern.
     *
     * Pattern: {verb}_{noun}_{context}[::attribute]
     *
     * Usage Examples:
     *
     * Basic permissions:
     * - PermissionHelper::make()->view()->course()->all()->build();
     *   Result: "view_course_all"
     *
     * - PermissionHelper::make()->manage()->user()->owner()->build();
     *   Result: "manage_user_owner"
     *
     * Attribute-based contexts:
     * - PermissionHelper::make()->update()->quiz()->id('quiz-123')->build();
     *   Result: "update_quiz_id::quiz-123"
     *
     * - PermissionHelper::make()->view()->assignment()->tag('advanced')->build();
     *   Result: "view_assignment_tag::advanced"
     *
     * Available verbs: create, view, update, delete, manage, assign, viewList, grade, attempt, submit, viewResults
     * Available contexts: all, owner, public, self, authentication, id(value), tag(value)
     * Available nouns: user, role, permission, course, quiz, assignment, enrollment, badge, question, submission, tag, media, etc.
     *
     * Special case - tag() method:
     * - As noun: PermissionHelper::make()->view()->tag()->all()->build() → "view_tag_all"
     * - As context: PermissionHelper::make()->view()->quiz()->tag('level-1')->build() → "view_quiz_tag::level-1"
     *
     * @return static New PermissionHelper instance for method chaining
     */
    public static function make(): static
    {
        return new static;
    }

    // ========== VERB HELPER METHODS ==========

    /**
     * Set verb to CREATE.
     */
    public function create(): self
    {
        return $this->verb(PermissionVerbEnum::CREATE);
    }

    /**
     * Set verb to VIEW.
     */
    public function view(): self
    {
        return $this->verb(PermissionVerbEnum::VIEW);
    }

    /**
     * Set verb to UPDATE.
     */
    public function update(): self
    {
        return $this->verb(PermissionVerbEnum::UPDATE);
    }

    /**
     * Set verb to DELETE.
     */
    public function delete(): self
    {
        return $this->verb(PermissionVerbEnum::DELETE);
    }

    /**
     * Set verb to ASSIGN.
     */
    public function assign(): self
    {
        return $this->verb(PermissionVerbEnum::ASSIGN);
    }

    /**
     * Set verb to MANAGE.
     */
    public function manage(): self
    {
        return $this->verb(PermissionVerbEnum::MANAGE);
    }

    /**
     * Set verb to VIEW_LIST.
     */
    public function viewList(): self
    {
        return $this->verb(PermissionVerbEnum::VIEW_LIST);
    }

    /**
     * Set verb to GRADE.
     */
    public function gradeVerb(): self
    {
        return $this->verb(PermissionVerbEnum::GRADE);
    }

    /**
     * Set verb to ATTEMPT.
     */
    public function attempt(): self
    {
        return $this->verb(PermissionVerbEnum::ATTEMPT);
    }

    /**
     * Set verb to SUBMIT.
     */
    public function submit(): self
    {
        return $this->verb(PermissionVerbEnum::SUBMIT);
    }

    /**
     * Set verb to VIEW_RESULTS.
     */
    public function viewResults(): self
    {
        return $this->verb(PermissionVerbEnum::VIEW_RESULTS);
    }

    // ========== EDUCATIONAL VERB METHODS ==========

    /**
     * Set verb to ENROLL.
     */
    public function enroll(): self
    {
        return $this->verb(PermissionVerbEnum::ENROLL);
    }

    /**
     * Set verb to TEACH.
     */
    public function teach(): self
    {
        return $this->verb(PermissionVerbEnum::TEACH);
    }

    /**
     * Set verb to REVIEW.
     */
    public function review(): self
    {
        return $this->verb(PermissionVerbEnum::REVIEW);
    }

    /**
     * Set verb to APPROVE.
     */
    public function approve(): self
    {
        return $this->verb(PermissionVerbEnum::APPROVE);
    }

    /**
     * Set verb to REJECT.
     */
    public function reject(): self
    {
        return $this->verb(PermissionVerbEnum::REJECT);
    }

    /**
     * Set verb to PUBLISH.
     */
    public function publish(): self
    {
        return $this->verb(PermissionVerbEnum::PUBLISH);
    }

    /**
     * Set verb to UNPUBLISH.
     */
    public function unpublish(): self
    {
        return $this->verb(PermissionVerbEnum::UNPUBLISH);
    }

    /**
     * Set verb to ARCHIVE.
     */
    public function archive(): self
    {
        return $this->verb(PermissionVerbEnum::ARCHIVE);
    }

    /**
     * Set verb to RESTORE.
     */
    public function restore(): self
    {
        return $this->verb(PermissionVerbEnum::RESTORE);
    }

    // ========== ADMINISTRATIVE VERB METHODS ==========

    /**
     * Set verb to CONFIGURE.
     */
    public function configure(): self
    {
        return $this->verb(PermissionVerbEnum::CONFIGURE);
    }

    /**
     * Set verb to MONITOR.
     */
    public function monitor(): self
    {
        return $this->verb(PermissionVerbEnum::MONITOR);
    }

    /**
     * Set verb to EXPORT.
     */
    public function export(): self
    {
        return $this->verb(PermissionVerbEnum::EXPORT);
    }

    /**
     * Set verb to IMPORT.
     */
    public function import(): self
    {
        return $this->verb(PermissionVerbEnum::IMPORT);
    }

    /**
     * Set verb to BACKUP.
     */
    public function backup(): self
    {
        return $this->verb(PermissionVerbEnum::BACKUP);
    }

    /**
     * Set verb to RESET.
     */
    public function reset(): self
    {
        return $this->verb(PermissionVerbEnum::RESET);
    }

    // ========== COMMUNICATION VERB METHODS ==========

    /**
     * Set verb to NOTIFY.
     */
    public function notify(): self
    {
        return $this->verb(PermissionVerbEnum::NOTIFY);
    }

    /**
     * Set verb to MESSAGE.
     */
    public function message(): self
    {
        return $this->verb(PermissionVerbEnum::MESSAGE);
    }

    /**
     * Set verb to ANNOUNCE.
     */
    public function announce(): self
    {
        return $this->verb(PermissionVerbEnum::ANNOUNCE);
    }

    // ========== PROGRESS TRACKING VERB METHODS ==========

    /**
     * Set verb to TRACK.
     */
    public function track(): self
    {
        return $this->verb(PermissionVerbEnum::TRACK);
    }

    /**
     * Set verb to EVALUATE.
     */
    public function evaluate(): self
    {
        return $this->verb(PermissionVerbEnum::EVALUATE);
    }

    /**
     * Set verb to CERTIFY.
     */
    public function certify(): self
    {
        return $this->verb(PermissionVerbEnum::CERTIFY);
    }

    // ========== QUIZ-SPECIFIC VERB METHODS ==========

    /**
     * Set verb to TAKE.
     */
    public function take(): self
    {
        return $this->verb(PermissionVerbEnum::TAKE);
    }

    /**
     * Set verb to RETAKE.
     */
    public function retake(): self
    {
        return $this->verb(PermissionVerbEnum::RETAKE);
    }

    /**
     * Set verb to START.
     */
    public function start(): self
    {
        return $this->verb(PermissionVerbEnum::START);
    }

    /**
     * Set verb to FINISH.
     */
    public function finish(): self
    {
        return $this->verb(PermissionVerbEnum::FINISH);
    }

    /**
     * Set verb to PAUSE.
     */
    public function pause(): self
    {
        return $this->verb(PermissionVerbEnum::PAUSE);
    }

    /**
     * Set verb to RESUME.
     */
    public function resume(): self
    {
        return $this->verb(PermissionVerbEnum::RESUME);
    }

    // ========== ASSIGNMENT-SPECIFIC VERB METHODS ==========

    /**
     * Set verb to DISTRIBUTE.
     */
    public function distribute(): self
    {
        return $this->verb(PermissionVerbEnum::DISTRIBUTE);
    }

    /**
     * Set verb to COLLECT.
     */
    public function collect(): self
    {
        return $this->verb(PermissionVerbEnum::COLLECT);
    }

    /**
     * Set verb to EXTEND.
     */
    public function extend(): self
    {
        return $this->verb(PermissionVerbEnum::EXTEND);
    }

    // ========== COURSE-SPECIFIC VERB METHODS ==========

    /**
     * Set verb to ATTEND.
     */
    public function attend(): self
    {
        return $this->verb(PermissionVerbEnum::ATTEND);
    }

    /**
     * Set verb to COMPLETE.
     */
    public function complete(): self
    {
        return $this->verb(PermissionVerbEnum::COMPLETE);
    }

    /**
     * Set verb to WITHDRAW.
     */
    public function withdraw(): self
    {
        return $this->verb(PermissionVerbEnum::WITHDRAW);
    }

    /**
     * Set verb to TRANSFER.
     */
    public function transfer(): self
    {
        return $this->verb(PermissionVerbEnum::TRANSFER);
    }

    // ========== USER-SPECIFIC VERB METHODS ==========

    /**
     * Set verb to INVITE.
     */
    public function invite(): self
    {
        return $this->verb(PermissionVerbEnum::INVITE);
    }

    /**
     * Set verb to SUSPEND.
     */
    public function suspend(): self
    {
        return $this->verb(PermissionVerbEnum::SUSPEND);
    }

    /**
     * Set verb to ACTIVATE.
     */
    public function activate(): self
    {
        return $this->verb(PermissionVerbEnum::ACTIVATE);
    }

    /**
     * Set verb to DEACTIVATE.
     */
    public function deactivate(): self
    {
        return $this->verb(PermissionVerbEnum::DEACTIVATE);
    }

    // ========== BADGE-SPECIFIC VERB METHODS ==========

    /**
     * Set verb to EARN.
     */
    public function earn(): self
    {
        return $this->verb(PermissionVerbEnum::EARN);
    }

    /**
     * Set verb to AWARD.
     */
    public function award(): self
    {
        return $this->verb(PermissionVerbEnum::AWARD);
    }

    /**
     * Set verb to REVOKE.
     */
    public function revoke(): self
    {
        return $this->verb(PermissionVerbEnum::REVOKE);
    }

    // ========== MEDIA-SPECIFIC VERB METHODS ==========

    /**
     * Set verb to UPLOAD.
     */
    public function upload(): self
    {
        return $this->verb(PermissionVerbEnum::UPLOAD);
    }

    /**
     * Set verb to DOWNLOAD.
     */
    public function download(): self
    {
        return $this->verb(PermissionVerbEnum::DOWNLOAD);
    }

    /**
     * Set verb to STREAM.
     */
    public function stream(): self
    {
        return $this->verb(PermissionVerbEnum::STREAM);
    }

    // ========== REPORT-SPECIFIC VERB METHODS ==========

    /**
     * Set verb to GENERATE.
     */
    public function generate(): self
    {
        return $this->verb(PermissionVerbEnum::GENERATE);
    }

    /**
     * Set verb to SCHEDULE.
     */
    public function schedule(): self
    {
        return $this->verb(PermissionVerbEnum::SCHEDULE);
    }

    /**
     * Set verb to ANALYZE.
     */
    public function analyze(): self
    {
        return $this->verb(PermissionVerbEnum::ANALYZE);
    }

    // ========== CONTEXT HELPER METHODS ==========

    /**
     * Set context to ALL.
     */
    public function all(): self
    {
        return $this->context(PermissionContextEnum::ALL);
    }

    /**
     * Set context to OWNER.
     */
    public function owner(): self
    {
        return $this->context(PermissionContextEnum::OWNER);
    }

    /**
     * Set context to PUBLIC.
     */
    public function public(): self
    {
        return $this->context(PermissionContextEnum::PUBLIC);
    }

    /**
     * Set context to AUTHENTICATION.
     */
    public function authentication(): self
    {
        return $this->context(PermissionContextEnum::AUTHENTICATION);
    }

    /**
     * Set context to SELF.
     */
    public function self(): self
    {
        return $this->context(PermissionContextEnum::SELF);
    }

    /**
     * Set context to ID with optional attribute value.
     *
     * @param  string|null  $value  The ID value
     */
    public function id(?string $value = null): self
    {
        $this->context(PermissionContextEnum::ID);
        if ($value !== null) {
            $this->withAttribute($value);
        }

        return $this;
    }

    /**
     * Set context to TAG with optional attribute value, or set noun to TAG if no noun is set.
     *
     * @param  string|null  $value  The tag value
     */
    public function tag(?string $value = null): self
    {
        // If no noun is set yet and no value provided, treat as noun
        if ($this->noun === null && $value === null) {
            return $this->noun(PermissionNounEnum::TAG);
        }

        // Otherwise, treat as context
        $this->context(PermissionContextEnum::TAG);
        if ($value !== null) {
            $this->withAttribute($value);
        }

        return $this;
    }

    /**
     * Set context to GRADE with optional attribute value.
     *
     * @param  string|null  $value  The grade value
     */
    public function grade(?string $value = null): self
    {
        $this->context(PermissionContextEnum::GRADE);
        if ($value !== null) {
            $this->withAttribute($value);
        }

        return $this;
    }

    /**
     * Set context to LEVEL with optional attribute value.
     *
     * @param  string|null  $value  The level value
     */
    public function level(?string $value = null): self
    {
        $this->context(PermissionContextEnum::LEVEL);
        if ($value !== null) {
            $this->withAttribute($value);
        }

        return $this;
    }

    // ========== ROLE-BASED CONTEXT METHODS ==========

    /**
     * Set context to ADMIN.
     */
    public function admin(): self
    {
        return $this->context(PermissionContextEnum::ADMIN);
    }

    /**
     * Set context to MANAGER.
     */
    public function manager(): self
    {
        return $this->context(PermissionContextEnum::MANAGER);
    }

    /**
     * Set context to TEACHER.
     */
    public function teacher(): self
    {
        return $this->context(PermissionContextEnum::TEACHER);
    }

    /**
     * Set context to STUDENT.
     */
    public function student(): self
    {
        return $this->context(PermissionContextEnum::STUDENT);
    }

    // ========== EDUCATIONAL CONTEXT METHODS ==========

    /**
     * Set context to ENROLLED.
     */
    public function enrolled(): self
    {
        return $this->context(PermissionContextEnum::ENROLLED);
    }

    /**
     * Set context to ASSIGNED.
     */
    public function assigned(): self
    {
        return $this->context(PermissionContextEnum::ASSIGNED);
    }

    /**
     * Set context to SUPERVISED.
     */
    public function supervised(): self
    {
        return $this->context(PermissionContextEnum::SUPERVISED);
    }

    /**
     * Set context to DEPARTMENT.
     */
    public function department(): self
    {
        return $this->context(PermissionContextEnum::DEPARTMENT);
    }

    /**
     * Set context to INSTITUTION.
     */
    public function institution(): self
    {
        return $this->context(PermissionContextEnum::INSTITUTION);
    }

    // ========== STATUS-BASED CONTEXT METHODS ==========

    /**
     * Set context to ACTIVE.
     */
    public function active(): self
    {
        return $this->context(PermissionContextEnum::ACTIVE);
    }

    /**
     * Set context to INACTIVE.
     */
    public function inactive(): self
    {
        return $this->context(PermissionContextEnum::INACTIVE);
    }

    /**
     * Set context to PENDING.
     */
    public function pending(): self
    {
        return $this->context(PermissionContextEnum::PENDING);
    }

    /**
     * Set context to COMPLETED.
     */
    public function completed(): self
    {
        return $this->context(PermissionContextEnum::COMPLETED);
    }

    /**
     * Set context to IN_PROGRESS.
     */
    public function inProgress(): self
    {
        return $this->context(PermissionContextEnum::IN_PROGRESS);
    }

    /**
     * Set context to DRAFT.
     */
    public function draft(): self
    {
        return $this->context(PermissionContextEnum::DRAFT);
    }

    /**
     * Set context to PUBLISHED.
     */
    public function published(): self
    {
        return $this->context(PermissionContextEnum::PUBLISHED);
    }

    /**
     * Set context to ARCHIVED.
     */
    public function archived(): self
    {
        return $this->context(PermissionContextEnum::ARCHIVED);
    }

    // ========== TIME-BASED CONTEXT METHODS ==========

    /**
     * Set context to CURRENT.
     */
    public function current(): self
    {
        return $this->context(PermissionContextEnum::CURRENT);
    }

    /**
     * Set context to PAST.
     */
    public function past(): self
    {
        return $this->context(PermissionContextEnum::PAST);
    }

    /**
     * Set context to FUTURE.
     */
    public function future(): self
    {
        return $this->context(PermissionContextEnum::FUTURE);
    }

    // ========== QUIZ-SPECIFIC CONTEXT METHODS ==========

    /**
     * Set context to TIMED.
     */
    public function timed(): self
    {
        return $this->context(PermissionContextEnum::TIMED);
    }

    /**
     * Set context to UNLIMITED.
     */
    public function unlimited(): self
    {
        return $this->context(PermissionContextEnum::UNLIMITED);
    }

    /**
     * Set context to LIMITED_ATTEMPTS.
     */
    public function limitedAttempts(): self
    {
        return $this->context(PermissionContextEnum::LIMITED_ATTEMPTS);
    }

    /**
     * Set context to RANDOMIZED.
     */
    public function randomized(): self
    {
        return $this->context(PermissionContextEnum::RANDOMIZED);
    }

    /**
     * Set context to SEQUENTIAL.
     */
    public function sequential(): self
    {
        return $this->context(PermissionContextEnum::SEQUENTIAL);
    }

    // ========== ASSIGNMENT-SPECIFIC CONTEXT METHODS ==========

    /**
     * Set context to GROUP.
     */
    public function group(): self
    {
        return $this->context(PermissionContextEnum::GROUP);
    }

    /**
     * Set context to INDIVIDUAL.
     */
    public function individual(): self
    {
        return $this->context(PermissionContextEnum::INDIVIDUAL);
    }

    /**
     * Set context to PEER_REVIEW.
     */
    public function peerReview(): self
    {
        return $this->context(PermissionContextEnum::PEER_REVIEW);
    }

    /**
     * Set context to AUTO_GRADED.
     */
    public function autoGraded(): self
    {
        return $this->context(PermissionContextEnum::AUTO_GRADED);
    }

    /**
     * Set context to MANUAL_GRADED.
     */
    public function manualGraded(): self
    {
        return $this->context(PermissionContextEnum::MANUAL_GRADED);
    }

    // ========== COURSE-SPECIFIC CONTEXT METHODS ==========

    /**
     * Set context to PREREQUISITE.
     */
    public function prerequisite(): self
    {
        return $this->context(PermissionContextEnum::PREREQUISITE);
    }

    /**
     * Set context to ELECTIVE.
     */
    public function elective(): self
    {
        return $this->context(PermissionContextEnum::ELECTIVE);
    }

    /**
     * Set context to MANDATORY.
     */
    public function mandatory(): self
    {
        return $this->context(PermissionContextEnum::MANDATORY);
    }

    /**
     * Set context to ONLINE.
     */
    public function online(): self
    {
        return $this->context(PermissionContextEnum::ONLINE);
    }

    /**
     * Set context to OFFLINE.
     */
    public function offline(): self
    {
        return $this->context(PermissionContextEnum::OFFLINE);
    }

    /**
     * Set context to HYBRID.
     */
    public function hybrid(): self
    {
        return $this->context(PermissionContextEnum::HYBRID);
    }

    // ========== USER-SPECIFIC CONTEXT METHODS ==========

    /**
     * Set context to VERIFIED.
     */
    public function verified(): self
    {
        return $this->context(PermissionContextEnum::VERIFIED);
    }

    /**
     * Set context to UNVERIFIED.
     */
    public function unverified(): self
    {
        return $this->context(PermissionContextEnum::UNVERIFIED);
    }

    /**
     * Set context to FIRST_TIME.
     */
    public function firstTime(): self
    {
        return $this->context(PermissionContextEnum::FIRST_TIME);
    }

    /**
     * Set context to RETURNING.
     */
    public function returning(): self
    {
        return $this->context(PermissionContextEnum::RETURNING);
    }

    /**
     * Set context to GUEST.
     */
    public function guest(): self
    {
        return $this->context(PermissionContextEnum::GUEST);
    }

    // ========== SUBMISSION-SPECIFIC CONTEXT METHODS ==========

    /**
     * Set context to ON_TIME.
     */
    public function onTime(): self
    {
        return $this->context(PermissionContextEnum::ON_TIME);
    }

    /**
     * Set context to LATE.
     */
    public function late(): self
    {
        return $this->context(PermissionContextEnum::LATE);
    }

    /**
     * Set context to EARLY.
     */
    public function early(): self
    {
        return $this->context(PermissionContextEnum::EARLY);
    }

    /**
     * Set context to RESUBMISSION.
     */
    public function resubmission(): self
    {
        return $this->context(PermissionContextEnum::RESUBMISSION);
    }

    // ========== BADGE-SPECIFIC CONTEXT METHODS ==========

    /**
     * Set context to ACHIEVEMENT.
     */
    public function achievement(): self
    {
        return $this->context(PermissionContextEnum::ACHIEVEMENT);
    }

    /**
     * Set context to PARTICIPATION.
     */
    public function participation(): self
    {
        return $this->context(PermissionContextEnum::PARTICIPATION);
    }

    /**
     * Set context to COMPLETION.
     */
    public function completion(): self
    {
        return $this->context(PermissionContextEnum::COMPLETION);
    }

    /**
     * Set context to EXCELLENCE.
     */
    public function excellence(): self
    {
        return $this->context(PermissionContextEnum::EXCELLENCE);
    }

    // ========== MEDIA-SPECIFIC CONTEXT METHODS ==========

    /**
     * Set context to VIDEO.
     */
    public function video(): self
    {
        return $this->context(PermissionContextEnum::VIDEO);
    }

    /**
     * Set context to AUDIO.
     */
    public function audio(): self
    {
        return $this->context(PermissionContextEnum::AUDIO);
    }

    /**
     * Set context to DOCUMENT.
     */
    public function document(): self
    {
        return $this->context(PermissionContextEnum::DOCUMENT);
    }

    /**
     * Set context to IMAGE.
     */
    public function image(): self
    {
        return $this->context(PermissionContextEnum::IMAGE);
    }

    /**
     * Set context to INTERACTIVE.
     */
    public function interactive(): self
    {
        return $this->context(PermissionContextEnum::INTERACTIVE);
    }

    // ========== MAGIC METHOD CALLS FOR NOUNS ==========

    /**
     * Magic method to handle noun method calls.
     *
     * @param  string  $name  The method name
     * @param  array  $arguments  The method arguments
     *
     * @throws InvalidArgumentException If the noun is not found
     */
    public function __call(string $name, array $arguments): self
    {
        // Special handling for 'tag' - if called without arguments, treat as noun
        // If called with arguments, treat as context method
        if ($name === 'tag' && empty($arguments)) {
            return $this->noun(PermissionNounEnum::TAG);
        }

        // Convert method name to uppercase for enum matching
        $enumName = strtoupper($name);

        // Try to find the enum case
        foreach (PermissionNounEnum::cases() as $case) {
            if ($case->name === $enumName) {
                return $this->noun($case);
            }
        }

        throw new InvalidArgumentException("Unknown method: {$name}");
    }
}

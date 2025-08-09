<?php

namespace App\Libs\Permissions;

use App\Enums\Permissions\PermissionVerbEnum;
use App\Enums\Permissions\PermissionNounEnum;
use App\Enums\Permissions\PermissionContextEnum;
use InvalidArgumentException;

/**
 * PermissionHelper
 *
 * A helper class that extends PermissionBuilder with fluent interface methods
 * for creating permission strings with convenient helper methods.
 *
 * @package App\Libs\Permissions
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
        return new static();
    }

    // ========== VERB HELPER METHODS ==========

    /**
     * Set verb to CREATE.
     *
     * @return self
     */
    public function create(): self
    {
        return $this->verb(PermissionVerbEnum::CREATE);
    }

    /**
     * Set verb to VIEW.
     *
     * @return self
     */
    public function view(): self
    {
        return $this->verb(PermissionVerbEnum::VIEW);
    }

    /**
     * Set verb to UPDATE.
     *
     * @return self
     */
    public function update(): self
    {
        return $this->verb(PermissionVerbEnum::UPDATE);
    }

    /**
     * Set verb to DELETE.
     *
     * @return self
     */
    public function delete(): self
    {
        return $this->verb(PermissionVerbEnum::DELETE);
    }

    /**
     * Set verb to ASSIGN.
     *
     * @return self
     */
    public function assign(): self
    {
        return $this->verb(PermissionVerbEnum::ASSIGN);
    }

    /**
     * Set verb to MANAGE.
     *
     * @return self
     */
    public function manage(): self
    {
        return $this->verb(PermissionVerbEnum::MANAGE);
    }

    /**
     * Set verb to VIEW_LIST.
     *
     * @return self
     */
    public function viewList(): self
    {
        return $this->verb(PermissionVerbEnum::VIEW_LIST);
    }

    /**
     * Set verb to GRADE.
     *
     * @return self
     */
    public function gradeVerb(): self
    {
        return $this->verb(PermissionVerbEnum::GRADE);
    }

    /**
     * Set verb to ATTEMPT.
     *
     * @return self
     */
    public function attempt(): self
    {
        return $this->verb(PermissionVerbEnum::ATTEMPT);
    }

    /**
     * Set verb to SUBMIT.
     *
     * @return self
     */
    public function submit(): self
    {
        return $this->verb(PermissionVerbEnum::SUBMIT);
    }

    /**
     * Set verb to VIEW_RESULTS.
     *
     * @return self
     */
    public function viewResults(): self
    {
        return $this->verb(PermissionVerbEnum::VIEW_RESULTS);
    }

    // ========== EDUCATIONAL VERB METHODS ==========

    /**
     * Set verb to ENROLL.
     *
     * @return self
     */
    public function enroll(): self
    {
        return $this->verb(PermissionVerbEnum::ENROLL);
    }

    /**
     * Set verb to TEACH.
     *
     * @return self
     */
    public function teach(): self
    {
        return $this->verb(PermissionVerbEnum::TEACH);
    }

    /**
     * Set verb to REVIEW.
     *
     * @return self
     */
    public function review(): self
    {
        return $this->verb(PermissionVerbEnum::REVIEW);
    }

    /**
     * Set verb to APPROVE.
     *
     * @return self
     */
    public function approve(): self
    {
        return $this->verb(PermissionVerbEnum::APPROVE);
    }

    /**
     * Set verb to REJECT.
     *
     * @return self
     */
    public function reject(): self
    {
        return $this->verb(PermissionVerbEnum::REJECT);
    }

    /**
     * Set verb to PUBLISH.
     *
     * @return self
     */
    public function publish(): self
    {
        return $this->verb(PermissionVerbEnum::PUBLISH);
    }

    /**
     * Set verb to UNPUBLISH.
     *
     * @return self
     */
    public function unpublish(): self
    {
        return $this->verb(PermissionVerbEnum::UNPUBLISH);
    }

    /**
     * Set verb to ARCHIVE.
     *
     * @return self
     */
    public function archive(): self
    {
        return $this->verb(PermissionVerbEnum::ARCHIVE);
    }

    /**
     * Set verb to RESTORE.
     *
     * @return self
     */
    public function restore(): self
    {
        return $this->verb(PermissionVerbEnum::RESTORE);
    }

    // ========== ADMINISTRATIVE VERB METHODS ==========

    /**
     * Set verb to CONFIGURE.
     *
     * @return self
     */
    public function configure(): self
    {
        return $this->verb(PermissionVerbEnum::CONFIGURE);
    }

    /**
     * Set verb to MONITOR.
     *
     * @return self
     */
    public function monitor(): self
    {
        return $this->verb(PermissionVerbEnum::MONITOR);
    }

    /**
     * Set verb to EXPORT.
     *
     * @return self
     */
    public function export(): self
    {
        return $this->verb(PermissionVerbEnum::EXPORT);
    }

    /**
     * Set verb to IMPORT.
     *
     * @return self
     */
    public function import(): self
    {
        return $this->verb(PermissionVerbEnum::IMPORT);
    }

    /**
     * Set verb to BACKUP.
     *
     * @return self
     */
    public function backup(): self
    {
        return $this->verb(PermissionVerbEnum::BACKUP);
    }

    /**
     * Set verb to RESET.
     *
     * @return self
     */
    public function reset(): self
    {
        return $this->verb(PermissionVerbEnum::RESET);
    }

    // ========== COMMUNICATION VERB METHODS ==========

    /**
     * Set verb to NOTIFY.
     *
     * @return self
     */
    public function notify(): self
    {
        return $this->verb(PermissionVerbEnum::NOTIFY);
    }

    /**
     * Set verb to MESSAGE.
     *
     * @return self
     */
    public function message(): self
    {
        return $this->verb(PermissionVerbEnum::MESSAGE);
    }

    /**
     * Set verb to ANNOUNCE.
     *
     * @return self
     */
    public function announce(): self
    {
        return $this->verb(PermissionVerbEnum::ANNOUNCE);
    }

    // ========== PROGRESS TRACKING VERB METHODS ==========

    /**
     * Set verb to TRACK.
     *
     * @return self
     */
    public function track(): self
    {
        return $this->verb(PermissionVerbEnum::TRACK);
    }

    /**
     * Set verb to EVALUATE.
     *
     * @return self
     */
    public function evaluate(): self
    {
        return $this->verb(PermissionVerbEnum::EVALUATE);
    }

    /**
     * Set verb to CERTIFY.
     *
     * @return self
     */
    public function certify(): self
    {
        return $this->verb(PermissionVerbEnum::CERTIFY);
    }

    // ========== QUIZ-SPECIFIC VERB METHODS ==========

    /**
     * Set verb to TAKE.
     *
     * @return self
     */
    public function take(): self
    {
        return $this->verb(PermissionVerbEnum::TAKE);
    }

    /**
     * Set verb to RETAKE.
     *
     * @return self
     */
    public function retake(): self
    {
        return $this->verb(PermissionVerbEnum::RETAKE);
    }

    /**
     * Set verb to START.
     *
     * @return self
     */
    public function start(): self
    {
        return $this->verb(PermissionVerbEnum::START);
    }

    /**
     * Set verb to FINISH.
     *
     * @return self
     */
    public function finish(): self
    {
        return $this->verb(PermissionVerbEnum::FINISH);
    }

    /**
     * Set verb to PAUSE.
     *
     * @return self
     */
    public function pause(): self
    {
        return $this->verb(PermissionVerbEnum::PAUSE);
    }

    /**
     * Set verb to RESUME.
     *
     * @return self
     */
    public function resume(): self
    {
        return $this->verb(PermissionVerbEnum::RESUME);
    }

    // ========== ASSIGNMENT-SPECIFIC VERB METHODS ==========

    /**
     * Set verb to DISTRIBUTE.
     *
     * @return self
     */
    public function distribute(): self
    {
        return $this->verb(PermissionVerbEnum::DISTRIBUTE);
    }

    /**
     * Set verb to COLLECT.
     *
     * @return self
     */
    public function collect(): self
    {
        return $this->verb(PermissionVerbEnum::COLLECT);
    }

    /**
     * Set verb to EXTEND.
     *
     * @return self
     */
    public function extend(): self
    {
        return $this->verb(PermissionVerbEnum::EXTEND);
    }

    // ========== COURSE-SPECIFIC VERB METHODS ==========

    /**
     * Set verb to ATTEND.
     *
     * @return self
     */
    public function attend(): self
    {
        return $this->verb(PermissionVerbEnum::ATTEND);
    }

    /**
     * Set verb to COMPLETE.
     *
     * @return self
     */
    public function complete(): self
    {
        return $this->verb(PermissionVerbEnum::COMPLETE);
    }

    /**
     * Set verb to WITHDRAW.
     *
     * @return self
     */
    public function withdraw(): self
    {
        return $this->verb(PermissionVerbEnum::WITHDRAW);
    }

    /**
     * Set verb to TRANSFER.
     *
     * @return self
     */
    public function transfer(): self
    {
        return $this->verb(PermissionVerbEnum::TRANSFER);
    }

    // ========== USER-SPECIFIC VERB METHODS ==========

    /**
     * Set verb to INVITE.
     *
     * @return self
     */
    public function invite(): self
    {
        return $this->verb(PermissionVerbEnum::INVITE);
    }

    /**
     * Set verb to SUSPEND.
     *
     * @return self
     */
    public function suspend(): self
    {
        return $this->verb(PermissionVerbEnum::SUSPEND);
    }

    /**
     * Set verb to ACTIVATE.
     *
     * @return self
     */
    public function activate(): self
    {
        return $this->verb(PermissionVerbEnum::ACTIVATE);
    }

    /**
     * Set verb to DEACTIVATE.
     *
     * @return self
     */
    public function deactivate(): self
    {
        return $this->verb(PermissionVerbEnum::DEACTIVATE);
    }

    // ========== BADGE-SPECIFIC VERB METHODS ==========

    /**
     * Set verb to EARN.
     *
     * @return self
     */
    public function earn(): self
    {
        return $this->verb(PermissionVerbEnum::EARN);
    }

    /**
     * Set verb to AWARD.
     *
     * @return self
     */
    public function award(): self
    {
        return $this->verb(PermissionVerbEnum::AWARD);
    }

    /**
     * Set verb to REVOKE.
     *
     * @return self
     */
    public function revoke(): self
    {
        return $this->verb(PermissionVerbEnum::REVOKE);
    }

    // ========== MEDIA-SPECIFIC VERB METHODS ==========

    /**
     * Set verb to UPLOAD.
     *
     * @return self
     */
    public function upload(): self
    {
        return $this->verb(PermissionVerbEnum::UPLOAD);
    }

    /**
     * Set verb to DOWNLOAD.
     *
     * @return self
     */
    public function download(): self
    {
        return $this->verb(PermissionVerbEnum::DOWNLOAD);
    }

    /**
     * Set verb to STREAM.
     *
     * @return self
     */
    public function stream(): self
    {
        return $this->verb(PermissionVerbEnum::STREAM);
    }

    // ========== REPORT-SPECIFIC VERB METHODS ==========

    /**
     * Set verb to GENERATE.
     *
     * @return self
     */
    public function generate(): self
    {
        return $this->verb(PermissionVerbEnum::GENERATE);
    }

    /**
     * Set verb to SCHEDULE.
     *
     * @return self
     */
    public function schedule(): self
    {
        return $this->verb(PermissionVerbEnum::SCHEDULE);
    }

    /**
     * Set verb to ANALYZE.
     *
     * @return self
     */
    public function analyze(): self
    {
        return $this->verb(PermissionVerbEnum::ANALYZE);
    }

    // ========== CONTEXT HELPER METHODS ==========

    /**
     * Set context to ALL.
     *
     * @return self
     */
    public function all(): self
    {
        return $this->context(PermissionContextEnum::ALL);
    }

    /**
     * Set context to OWNER.
     *
     * @return self
     */
    public function owner(): self
    {
        return $this->context(PermissionContextEnum::OWNER);
    }

    /**
     * Set context to PUBLIC.
     *
     * @return self
     */
    public function public(): self
    {
        return $this->context(PermissionContextEnum::PUBLIC);
    }

    /**
     * Set context to AUTHENTICATION.
     *
     * @return self
     */
    public function authentication(): self
    {
        return $this->context(PermissionContextEnum::AUTHENTICATION);
    }

    /**
     * Set context to SELF.
     *
     * @return self
     */
    public function self(): self
    {
        return $this->context(PermissionContextEnum::SELF);
    }

    /**
     * Set context to ID with optional attribute value.
     *
     * @param string|null $value The ID value
     * @return self
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
     * @param string|null $value The tag value
     * @return self
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
     * @param string|null $value The grade value
     * @return self
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
     * @param string|null $value The level value
     * @return self
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
     *
     * @return self
     */
    public function admin(): self
    {
        return $this->context(PermissionContextEnum::ADMIN);
    }

    /**
     * Set context to MANAGER.
     *
     * @return self
     */
    public function manager(): self
    {
        return $this->context(PermissionContextEnum::MANAGER);
    }

    /**
     * Set context to TEACHER.
     *
     * @return self
     */
    public function teacher(): self
    {
        return $this->context(PermissionContextEnum::TEACHER);
    }

    /**
     * Set context to STUDENT.
     *
     * @return self
     */
    public function student(): self
    {
        return $this->context(PermissionContextEnum::STUDENT);
    }

    // ========== EDUCATIONAL CONTEXT METHODS ==========

    /**
     * Set context to ENROLLED.
     *
     * @return self
     */
    public function enrolled(): self
    {
        return $this->context(PermissionContextEnum::ENROLLED);
    }

    /**
     * Set context to ASSIGNED.
     *
     * @return self
     */
    public function assigned(): self
    {
        return $this->context(PermissionContextEnum::ASSIGNED);
    }

    /**
     * Set context to SUPERVISED.
     *
     * @return self
     */
    public function supervised(): self
    {
        return $this->context(PermissionContextEnum::SUPERVISED);
    }

    /**
     * Set context to DEPARTMENT.
     *
     * @return self
     */
    public function department(): self
    {
        return $this->context(PermissionContextEnum::DEPARTMENT);
    }

    /**
     * Set context to INSTITUTION.
     *
     * @return self
     */
    public function institution(): self
    {
        return $this->context(PermissionContextEnum::INSTITUTION);
    }

    // ========== STATUS-BASED CONTEXT METHODS ==========

    /**
     * Set context to ACTIVE.
     *
     * @return self
     */
    public function active(): self
    {
        return $this->context(PermissionContextEnum::ACTIVE);
    }

    /**
     * Set context to INACTIVE.
     *
     * @return self
     */
    public function inactive(): self
    {
        return $this->context(PermissionContextEnum::INACTIVE);
    }

    /**
     * Set context to PENDING.
     *
     * @return self
     */
    public function pending(): self
    {
        return $this->context(PermissionContextEnum::PENDING);
    }

    /**
     * Set context to COMPLETED.
     *
     * @return self
     */
    public function completed(): self
    {
        return $this->context(PermissionContextEnum::COMPLETED);
    }

    /**
     * Set context to IN_PROGRESS.
     *
     * @return self
     */
    public function inProgress(): self
    {
        return $this->context(PermissionContextEnum::IN_PROGRESS);
    }

    /**
     * Set context to DRAFT.
     *
     * @return self
     */
    public function draft(): self
    {
        return $this->context(PermissionContextEnum::DRAFT);
    }

    /**
     * Set context to PUBLISHED.
     *
     * @return self
     */
    public function published(): self
    {
        return $this->context(PermissionContextEnum::PUBLISHED);
    }

    /**
     * Set context to ARCHIVED.
     *
     * @return self
     */
    public function archived(): self
    {
        return $this->context(PermissionContextEnum::ARCHIVED);
    }

    // ========== TIME-BASED CONTEXT METHODS ==========

    /**
     * Set context to CURRENT.
     *
     * @return self
     */
    public function current(): self
    {
        return $this->context(PermissionContextEnum::CURRENT);
    }

    /**
     * Set context to PAST.
     *
     * @return self
     */
    public function past(): self
    {
        return $this->context(PermissionContextEnum::PAST);
    }

    /**
     * Set context to FUTURE.
     *
     * @return self
     */
    public function future(): self
    {
        return $this->context(PermissionContextEnum::FUTURE);
    }

    // ========== QUIZ-SPECIFIC CONTEXT METHODS ==========

    /**
     * Set context to TIMED.
     *
     * @return self
     */
    public function timed(): self
    {
        return $this->context(PermissionContextEnum::TIMED);
    }

    /**
     * Set context to UNLIMITED.
     *
     * @return self
     */
    public function unlimited(): self
    {
        return $this->context(PermissionContextEnum::UNLIMITED);
    }

    /**
     * Set context to LIMITED_ATTEMPTS.
     *
     * @return self
     */
    public function limitedAttempts(): self
    {
        return $this->context(PermissionContextEnum::LIMITED_ATTEMPTS);
    }

    /**
     * Set context to RANDOMIZED.
     *
     * @return self
     */
    public function randomized(): self
    {
        return $this->context(PermissionContextEnum::RANDOMIZED);
    }

    /**
     * Set context to SEQUENTIAL.
     *
     * @return self
     */
    public function sequential(): self
    {
        return $this->context(PermissionContextEnum::SEQUENTIAL);
    }

    // ========== ASSIGNMENT-SPECIFIC CONTEXT METHODS ==========

    /**
     * Set context to GROUP.
     *
     * @return self
     */
    public function group(): self
    {
        return $this->context(PermissionContextEnum::GROUP);
    }

    /**
     * Set context to INDIVIDUAL.
     *
     * @return self
     */
    public function individual(): self
    {
        return $this->context(PermissionContextEnum::INDIVIDUAL);
    }

    /**
     * Set context to PEER_REVIEW.
     *
     * @return self
     */
    public function peerReview(): self
    {
        return $this->context(PermissionContextEnum::PEER_REVIEW);
    }

    /**
     * Set context to AUTO_GRADED.
     *
     * @return self
     */
    public function autoGraded(): self
    {
        return $this->context(PermissionContextEnum::AUTO_GRADED);
    }

    /**
     * Set context to MANUAL_GRADED.
     *
     * @return self
     */
    public function manualGraded(): self
    {
        return $this->context(PermissionContextEnum::MANUAL_GRADED);
    }

    // ========== COURSE-SPECIFIC CONTEXT METHODS ==========

    /**
     * Set context to PREREQUISITE.
     *
     * @return self
     */
    public function prerequisite(): self
    {
        return $this->context(PermissionContextEnum::PREREQUISITE);
    }

    /**
     * Set context to ELECTIVE.
     *
     * @return self
     */
    public function elective(): self
    {
        return $this->context(PermissionContextEnum::ELECTIVE);
    }

    /**
     * Set context to MANDATORY.
     *
     * @return self
     */
    public function mandatory(): self
    {
        return $this->context(PermissionContextEnum::MANDATORY);
    }

    /**
     * Set context to ONLINE.
     *
     * @return self
     */
    public function online(): self
    {
        return $this->context(PermissionContextEnum::ONLINE);
    }

    /**
     * Set context to OFFLINE.
     *
     * @return self
     */
    public function offline(): self
    {
        return $this->context(PermissionContextEnum::OFFLINE);
    }

    /**
     * Set context to HYBRID.
     *
     * @return self
     */
    public function hybrid(): self
    {
        return $this->context(PermissionContextEnum::HYBRID);
    }

    // ========== USER-SPECIFIC CONTEXT METHODS ==========

    /**
     * Set context to VERIFIED.
     *
     * @return self
     */
    public function verified(): self
    {
        return $this->context(PermissionContextEnum::VERIFIED);
    }

    /**
     * Set context to UNVERIFIED.
     *
     * @return self
     */
    public function unverified(): self
    {
        return $this->context(PermissionContextEnum::UNVERIFIED);
    }

    /**
     * Set context to FIRST_TIME.
     *
     * @return self
     */
    public function firstTime(): self
    {
        return $this->context(PermissionContextEnum::FIRST_TIME);
    }

    /**
     * Set context to RETURNING.
     *
     * @return self
     */
    public function returning(): self
    {
        return $this->context(PermissionContextEnum::RETURNING);
    }

    /**
     * Set context to GUEST.
     *
     * @return self
     */
    public function guest(): self
    {
        return $this->context(PermissionContextEnum::GUEST);
    }

    // ========== SUBMISSION-SPECIFIC CONTEXT METHODS ==========

    /**
     * Set context to ON_TIME.
     *
     * @return self
     */
    public function onTime(): self
    {
        return $this->context(PermissionContextEnum::ON_TIME);
    }

    /**
     * Set context to LATE.
     *
     * @return self
     */
    public function late(): self
    {
        return $this->context(PermissionContextEnum::LATE);
    }

    /**
     * Set context to EARLY.
     *
     * @return self
     */
    public function early(): self
    {
        return $this->context(PermissionContextEnum::EARLY);
    }

    /**
     * Set context to RESUBMISSION.
     *
     * @return self
     */
    public function resubmission(): self
    {
        return $this->context(PermissionContextEnum::RESUBMISSION);
    }

    // ========== BADGE-SPECIFIC CONTEXT METHODS ==========

    /**
     * Set context to ACHIEVEMENT.
     *
     * @return self
     */
    public function achievement(): self
    {
        return $this->context(PermissionContextEnum::ACHIEVEMENT);
    }

    /**
     * Set context to PARTICIPATION.
     *
     * @return self
     */
    public function participation(): self
    {
        return $this->context(PermissionContextEnum::PARTICIPATION);
    }

    /**
     * Set context to COMPLETION.
     *
     * @return self
     */
    public function completion(): self
    {
        return $this->context(PermissionContextEnum::COMPLETION);
    }

    /**
     * Set context to EXCELLENCE.
     *
     * @return self
     */
    public function excellence(): self
    {
        return $this->context(PermissionContextEnum::EXCELLENCE);
    }

    // ========== MEDIA-SPECIFIC CONTEXT METHODS ==========

    /**
     * Set context to VIDEO.
     *
     * @return self
     */
    public function video(): self
    {
        return $this->context(PermissionContextEnum::VIDEO);
    }

    /**
     * Set context to AUDIO.
     *
     * @return self
     */
    public function audio(): self
    {
        return $this->context(PermissionContextEnum::AUDIO);
    }

    /**
     * Set context to DOCUMENT.
     *
     * @return self
     */
    public function document(): self
    {
        return $this->context(PermissionContextEnum::DOCUMENT);
    }

    /**
     * Set context to IMAGE.
     *
     * @return self
     */
    public function image(): self
    {
        return $this->context(PermissionContextEnum::IMAGE);
    }

    /**
     * Set context to INTERACTIVE.
     *
     * @return self
     */
    public function interactive(): self
    {
        return $this->context(PermissionContextEnum::INTERACTIVE);
    }

    // ========== MAGIC METHOD CALLS FOR NOUNS ==========

    /**
     * Magic method to handle noun method calls.
     *
     * @param string $name The method name
     * @param array $arguments The method arguments
     * @return self
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

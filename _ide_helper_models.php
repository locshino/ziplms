<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */

namespace App\Models{
    /**
     * @property string $id
     * @property string $assignment_id
     * @property string $user_id
     * @property string|null $submission_text
     * @property Status $status
     * @property string $submission_date
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \App\Models\Assignment $assignment
     * @property-read \App\Models\AssignmentGrade|null $grade
     * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
     * @property-read int|null $media_count
     * @property-read \App\Models\User $user
     *
     * @method static \Database\Factories\AssignmentSubmissionFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission orWhereNotState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission orWhereState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereAssignmentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereNotState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereSubmissionDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereSubmissionText($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class AssignmentSubmission extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string $schedule_id
     * @property string $user_id
     * @property string $attended_at
     * @property array<array-key, mixed>|null $notes
     * @property string|null $marked_by
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \App\Models\User|null $marker
     * @property-read \App\Models\Schedule $schedule
     * @property-read \App\Models\User $user
     *
     * @method static \Database\Factories\AttendanceFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereAttendedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereMarkedBy($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereNotes($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereScheduleId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class Attendance extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string|null $organization_id
     * @property array<array-key, mixed> $name
     * @property array<array-key, mixed>|null $description
     * @property array<array-key, mixed>|null $criteria_description
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \App\Models\Organization|null $organization
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
     * @property-read int|null $users_count
     *
     * @method static \Database\Factories\BadgeFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereCriteriaDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereOrganizationId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge withoutTrashed()
     *
     * @mixin \Eloquent
     *
     * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
     * @property-read int|null $media_count
     * @property-read mixed $translations
     *
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereLocale(string $column, string $locale)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereLocales(string $column, array $locales)
     */
    class Badge extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string $organization_id
     * @property array<array-key, mixed> $name
     * @property string|null $code
     * @property array<array-key, mixed>|null $description
     * @property string|null $parent_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, ClassesMajor> $children
     * @property-read int|null $children_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserClassMajorEnrollment> $enrollments
     * @property-read int|null $enrollments_count
     * @property-read \App\Models\Organization $organization
     * @property-read ClassesMajor|null $parent
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Schedule> $schedules
     * @property-read int|null $schedules_count
     * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
     * @property-read int|null $tags_count
     * @property-read mixed $translations
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
     * @property-read int|null $users_count
     *
     * @method static \Database\Factories\ClassesMajorFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereCode($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereLocale(string $column, string $locale)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereLocales(string $column, array $locales)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereOrganizationId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereParentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor withAllTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor withAnyTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor withAnyTagsOfType(array|string $type)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class ClassesMajor extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string|null $sender_id
     * @property string|null $receiver_id
     * @property array<array-key, mixed>|null $subject
     * @property string $message
     * @property string $sent_at
     * @property \Illuminate\Support\Carbon|null $read_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \App\Models\User|null $receiver
     * @property-read \App\Models\User|null $sender
     *
     * @method static \Database\Factories\ContactMessageFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereMessage($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereReadAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereReceiverId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereSenderId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereSentAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereSubject($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class ContactMessage extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string|null $organization_id
     * @property array<array-key, mixed> $name
     * @property string|null $code
     * @property array<array-key, mixed>|null $description
     * @property string|null $parent_id
     * @property string|null $created_by
     * @property \Illuminate\Support\Carbon|null $start_date
     * @property \Illuminate\Support\Carbon|null $end_date
     * @property Status $status
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assignment> $assignments
     * @property-read int|null $assignments_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, Course> $children
     * @property-read int|null $children_count
     * @property-read \App\Models\User|null $creator
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CourseEnrollment> $enrollments
     * @property-read int|null $enrollments_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Exam> $exams
     * @property-read int|null $exams_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lecture> $lectures
     * @property-read int|null $lectures_count
     * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
     * @property-read int|null $media_count
     * @property-read \App\Models\Organization|null $organization
     * @property-read Course|null $parent
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Schedule> $schedules
     * @property-read int|null $schedules_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $staff
     * @property-read int|null $staff_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CourseStaffAssignment> $staffAssignments
     * @property-read int|null $staff_assignments_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $students
     * @property-read int|null $students_count
     * @property-read mixed $translations
     *
     * @method static \Database\Factories\CourseFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course orWhereNotState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course orWhereState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereCode($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereCreatedBy($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereEndDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereLocale(string $column, string $locale)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereLocales(string $column, array $locales)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereNotState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereOrganizationId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereParentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereStartDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course withoutTrashed()
     *
     * @mixin \Eloquent
     *
     * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
     * @property-read int|null $tags_count
     *
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course withAllTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course withAnyTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course withAnyTagsOfType(array|string $type)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Course withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     */
    class Course extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string $user_id
     * @property string $course_id
     * @property string|null $final_grade
     * @property Status $status
     * @property string $enrollment_date
     * @property \Illuminate\Support\Carbon|null $completed_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \App\Models\Course $course
     * @property-read \App\Models\User $user
     *
     * @method static \Database\Factories\CourseEnrollmentFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment orWhereNotState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment orWhereState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereCompletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereCourseId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereEnrollmentDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereFinalGrade($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereNotState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class CourseEnrollment extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string $user_id
     * @property string $course_id
     * @property \Illuminate\Support\Carbon $assigned_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \App\Models\Course $course
     * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
     * @property-read int|null $tags_count
     * @property-read \App\Models\User $user
     *
     * @method static \Database\Factories\CourseStaffAssignmentFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment whereAssignedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment whereCourseId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment withAllTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment withAnyTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment withAnyTagsOfType(array|string $type)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class CourseStaffAssignment extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string|null $organization_id
     * @property array<array-key, mixed> $title
     * @property array<array-key, mixed>|null $description
     * @property \Illuminate\Support\Carbon $start_time
     * @property \Illuminate\Support\Carbon|null $end_time
     * @property string|null $location
     * @property string|null $created_by
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \App\Models\User|null $creator
     * @property-read \App\Models\Organization|null $organization
     *
     * @method static \Database\Factories\EventFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Event onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Event query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedBy($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereEndTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereLocation($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereOrganizationId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereStartTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class Event extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string|null $course_id
     * @property string|null $lecture_id
     * @property array<array-key, mixed> $title
     * @property array<array-key, mixed>|null $description
     * @property \Illuminate\Support\Carbon|null $start_time
     * @property \Illuminate\Support\Carbon|null $end_time
     * @property int|null $duration_minutes
     * @property int $max_attempts
     * @property string|null $passing_score
     * @property bool $shuffle_questions
     * @property bool $shuffle_answers
     * @property ExamShowResultsType $show_results_after
     * @property string|null $created_by
     * @property Status $status
     * @property string|null $results_visible_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamAttempt> $attempts
     * @property-read int|null $attempts_count
     * @property-read \App\Models\Course|null $course
     * @property-read \App\Models\User|null $creator
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamQuestion> $examQuestions
     * @property-read int|null $exam_questions_count
     * @property-read \App\Models\Lecture|null $lecture
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Question> $questions
     * @property-read int|null $questions_count
     * @property-read mixed $translations
     *
     * @method static \Database\Factories\ExamFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam orWhereNotState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam orWhereState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereCourseId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereCreatedBy($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereDurationMinutes($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereEndTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereLectureId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereLocale(string $column, string $locale)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereLocales(string $column, array $locales)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereMaxAttempts($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereNotState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam wherePassingScore($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereResultsVisibleAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereShowResultsAfter($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereShuffleAnswers($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereShuffleQuestions($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereStartTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class Exam extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string $exam_attempt_id
     * @property string $exam_question_id
     * @property string $question_id
     * @property string|null $graded_by
     * @property string|null $selected_choice_id
     * @property array<array-key, mixed>|null $answer_text
     * @property array<array-key, mixed>|null $chosen_option_ids
     * @property string|null $points_earned
     * @property bool|null $is_correct
     * @property array<array-key, mixed>|null $teacher_feedback
     * @property \Illuminate\Support\Carbon|null $graded_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \App\Models\ExamAttempt $examAttempt
     * @property-read \App\Models\ExamQuestion $examQuestion
     * @property-read \App\Models\User|null $grader
     * @property-read \App\Models\Question $question
     * @property-read \App\Models\QuestionChoice|null $selectedChoice
     * @property-read mixed $translations
     *
     * @method static \Database\Factories\ExamAnswerFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereAnswerText($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereChosenOptionIds($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereExamAttemptId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereExamQuestionId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereGradedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereGradedBy($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereIsCorrect($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereLocale(string $column, string $locale)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereLocales(string $column, array $locales)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer wherePointsEarned($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereQuestionId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereSelectedChoiceId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereTeacherFeedback($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class ExamAnswer extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string $exam_id
     * @property string $user_id
     * @property int $attempt_number
     * @property string|null $score
     * @property int|null $time_spent_seconds
     * @property array<array-key, mixed>|null $feedback
     * @property Status $status
     * @property \Illuminate\Support\Carbon|null $started_at
     * @property \Illuminate\Support\Carbon|null $completed_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamAnswer> $answers
     * @property-read int|null $answers_count
     * @property-read \App\Models\Exam $exam
     * @property-read mixed $translations
     * @property-read \App\Models\User $user
     *
     * @method static \Database\Factories\ExamAttemptFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt orWhereNotState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt orWhereState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereAttemptNumber($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereCompletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereExamId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereFeedback($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereLocale(string $column, string $locale)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereLocales(string $column, array $locales)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereNotState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereScore($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereStartedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereTimeSpentSeconds($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class ExamAttempt extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string $exam_id
     * @property string $question_id
     * @property string $points
     * @property int $question_order
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \App\Models\Exam $exam
     * @property-read \App\Models\Question $question
     *
     * @method static \Database\Factories\ExamQuestionFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereExamId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion wherePoints($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereQuestionId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereQuestionOrder($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class ExamQuestion extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string $course_id
     * @property array<array-key, mixed> $title
     * @property array<array-key, mixed>|null $description
     * @property int $lecture_order
     * @property string|null $duration_estimate
     * @property string|null $created_by
     * @property Status $status
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \App\Models\Course $course
     * @property-read \App\Models\User|null $creator
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Exam> $exams
     * @property-read int|null $exams_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LectureMaterial> $materials
     * @property-read int|null $materials_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Schedule> $schedules
     * @property-read int|null $schedules_count
     * @property-read mixed $translations
     *
     * @method static \Database\Factories\LectureFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture orWhereNotState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture orWhereState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereCourseId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereCreatedBy($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereDurationEstimate($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereLectureOrder($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereLocale(string $column, string $locale)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereLocales(string $column, array $locales)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereNotState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class Lecture extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string $lecture_id
     * @property array<array-key, mixed> $name
     * @property array<array-key, mixed>|null $description
     * @property string|null $uploaded_by
     * @property array<array-key, mixed>|null $video_links
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \App\Models\Lecture $lecture
     * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
     * @property-read int|null $media_count
     * @property-read mixed $translations
     * @property-read \App\Models\User|null $uploader
     *
     * @method static \Database\Factories\LectureMaterialFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereLectureId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereLocale(string $column, string $locale)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereLocales(string $column, array $locales)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereUploadedBy($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereVideoLinks($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class LectureMaterial extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
    /**
     * @property int $id
     * @property string $code
     * @property string $email
     * @property string $expires_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OTPLogin newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OTPLogin newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OTPLogin query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OTPLogin whereCode($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OTPLogin whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OTPLogin whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OTPLogin whereExpiresAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OTPLogin whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OTPLogin whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class OTPLogin extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string $name
     * @property string|null $slug
     * @property string|null $address
     * @property array<array-key, mixed>|null $settings
     * @property string|null $phone_number
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Badge> $badges
     * @property-read int|null $badges_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Batch> $batches
     * @property-read int|null $batches_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClassesMajor> $classesMajors
     * @property-read int|null $classes_majors_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $courses
     * @property-read int|null $courses_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
     * @property-read int|null $events_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Question> $questions
     * @property-read int|null $questions_count
     * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
     * @property-read int|null $tags_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
     * @property-read int|null $users_count
     *
     * @method static \Database\Factories\OrganizationFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereAddress($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization wherePhoneNumber($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereSettings($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereSlug($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization withAllTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization withAnyTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization withAnyTagsOfType(array|string $type)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class Organization extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string $organization_id
     * @property string $user_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \App\Models\Organization $organization
     * @property-read \App\Models\User $user
     *
     * @method static \Database\Factories\OrganizationUserFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser whereOrganizationId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class OrganizationUser extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string $name
     * @property string $guard_name
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, Permission> $permissions
     * @property-read int|null $permissions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
     * @property-read int|null $roles_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
     * @property-read int|null $users_count
     *
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission permission($permissions, $without = false)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission role($roles, $guard = null, $without = false)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereGuardName($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withoutPermission($permissions)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withoutRole($roles, $guard = null)
     *
     * @mixin \Eloquent
     */
    class Permission extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string|null $organization_id
     * @property array<array-key, mixed> $question_text
     * @property array<array-key, mixed>|null $explanation
     * @property string|null $created_by
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QuestionChoice> $choices
     * @property-read int|null $choices_count
     * @property-read \App\Models\User|null $creator
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Exam> $exams
     * @property-read int|null $exams_count
     * @property-read \App\Models\Organization|null $organization
     * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
     * @property-read int|null $tags_count
     * @property-read mixed $translations
     *
     * @method static \Database\Factories\QuestionFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereCreatedBy($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereExplanation($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereLocale(string $column, string $locale)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereLocales(string $column, array $locales)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereOrganizationId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereQuestionText($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question withAllTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question withAnyTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question withAnyTagsOfType(array|string $type)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Question withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class Question extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string $question_id
     * @property array<array-key, mixed> $choice_text
     * @property bool $is_correct
     * @property int $choice_order
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \App\Models\Question $question
     * @property-read mixed $translations
     *
     * @method static \Database\Factories\QuestionChoiceFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereChoiceOrder($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereChoiceText($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereIsCorrect($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereLocale(string $column, string $locale)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereLocales(string $column, array $locales)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereQuestionId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class QuestionChoice extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string $name
     * @property string $guard_name
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission> $permissions
     * @property-read int|null $permissions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
     * @property-read int|null $users_count
     *
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role permission($permissions, $without = false)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereGuardName($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withoutPermission($permissions)
     *
     * @mixin \Eloquent
     */
    class Role extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string $schedulable_type
     * @property string $schedulable_id
     * @property array<array-key, mixed> $title
     * @property array<array-key, mixed>|null $description
     * @property string|null $assigned_teacher_id
     * @property \Illuminate\Support\Carbon $start_time
     * @property \Illuminate\Support\Carbon $end_time
     * @property string|null $location_details
     * @property string|null $created_by
     * @property Status $status
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \App\Models\User|null $assignedTeacher
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance> $attendances
     * @property-read int|null $attendances_count
     * @property-read \App\Models\User|null $creator
     * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $schedulable
     * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
     * @property-read int|null $tags_count
     * @property-read mixed $translations
     *
     * @method static \Database\Factories\ScheduleFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule orWhereNotState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule orWhereState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereAssignedTeacherId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereCreatedBy($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereEndTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereLocale(string $column, string $locale)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereLocales(string $column, array $locales)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereLocationDetails($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereNotState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereSchedulableId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereSchedulableType($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereStartTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule withAllTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule withAnyTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule withAnyTagsOfType(array|string $type)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class Schedule extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string|null $code
     * @property string $name
     * @property string $email
     * @property string $password
     * @property string|null $phone_number
     * @property string|null $address
     * @property string|null $last_login_at
     * @property \Illuminate\Support\Carbon|null $email_verified_at
     * @property string|null $remember_token
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property Status $status
     * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
     * @property-read int|null $media_count
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
     * @property-read int|null $notifications_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\OneTimePasswords\Models\OneTimePassword> $oneTimePasswords
     * @property-read int|null $one_time_passwords_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission> $permissions
     * @property-read int|null $permissions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
     * @property-read int|null $roles_count
     *
     * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User orWhereNotState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User orWhereState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAddress($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCode($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLoginAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNotState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoneNumber($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereState(string $column, $states)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class User extends \Eloquent implements \Filament\Models\Contracts\FilamentUser, \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string $user_id
     * @property string $badge_id
     * @property string $awarded_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \App\Models\Badge $badge
     * @property-read \App\Models\User $user
     *
     * @method static \Database\Factories\UserBadgeFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereAwardedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereBadgeId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class UserBadge extends \Eloquent {}
}

namespace App\Models{
    /**
     * @property string $id
     * @property string $user_id
     * @property string $class_major_id
     * @property \Illuminate\Support\Carbon|null $start_date
     * @property \Illuminate\Support\Carbon|null $end_date
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \App\Models\ClassesMajor $classMajor
     * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
     * @property-read int|null $tags_count
     * @property-read \App\Models\User $user
     *
     * @method static \Database\Factories\UserClassMajorEnrollmentFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment whereClassMajorId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment whereEndDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment whereStartDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment withAllTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment withAnyTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment withAnyTagsOfType(array|string $type)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class UserClassMajorEnrollment extends \Eloquent {}
}

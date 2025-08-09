# PermissionHelper Methods Guide

This guide explains how to use the helper methods in the `PermissionHelper` class to build permissions using the fluent API.

## Basic Usage Pattern

```php
use App\Libs\Permissions\PermissionHelper;

// Basic pattern: verb -> noun -> context
$permission = PermissionHelper::create()
    ->view()
    ->course()
    ->student()
    ->build();
// Result: "VIEW_COURSE_STUDENT"
```

## Verb Methods

### CRUD Operations

```php
->create()     // CREATE
->read()       // READ
->update()     // UPDATE
->delete()     // DELETE
->view()       // VIEW
->edit()       // EDIT
->manage()     // MANAGE
```

### Educational Operations

```php
->enroll()     // ENROLL
->teach()      // TEACH
->review()     // REVIEW
->approve()    // APPROVE
->reject()     // REJECT
->publish()    // PUBLISH
->unpublish()  // UNPUBLISH
->archive()    // ARCHIVE
->restore()    // RESTORE
```

### Quiz-Specific Operations

```php
->take()       // TAKE
->retake()     // RETAKE
->start()      // START
->finish()     // FINISH
->pause()      // PAUSE
->resume()     // RESUME
->attempt()    // ATTEMPT
->grade()      // GRADE
```

### Assignment Operations

```php
->assign()     // ASSIGN
->submit()     // SUBMIT
->distribute() // DISTRIBUTE
->collect()    // COLLECT
->extend()     // EXTEND
```

### Course Operations

```php
->attend()     // ATTEND
->complete()   // COMPLETE
->withdraw()   // WITHDRAW
->transfer()   // TRANSFER
```

### User Management

```php
->invite()     // INVITE
->suspend()    // SUSPEND
->activate()   // ACTIVATE
->deactivate() // DEACTIVATE
```

### Badge Operations

```php
->earn()       // EARN
->award()      // AWARD
->revoke()     // REVOKE
```

### Media Operations

```php
->upload()     // UPLOAD
->download()   // DOWNLOAD
->stream()     // STREAM
```

### Report Operations

```php
->generate()   // GENERATE
->schedule()   // SCHEDULE
->analyze()    // ANALYZE
```

### Administrative Operations

```php
->configure()  // CONFIGURE
->monitor()    // MONITOR
->export()     // EXPORT
->import()     // IMPORT
->backup()     // BACKUP
->reset()      // RESET
```

### Communication Operations

```php
->notify()     // NOTIFY
->message()    // MESSAGE
->announce()   // ANNOUNCE
```

### Progress Tracking

```php
->track()      // TRACK
->evaluate()   // EVALUATE
->certify()    // CERTIFY
```

## Context Methods

### Role-Based Contexts

```php
->admin()      // ADMIN
->manager()    // MANAGER
->teacher()    // TEACHER
->student()    // STUDENT
```

### Educational Contexts

```php
->enrolled()   // ENROLLED
->assigned()   // ASSIGNED
->supervised() // SUPERVISED
->department() // DEPARTMENT
->institution() // INSTITUTION
```

### Status-Based Contexts

```php
->active()     // ACTIVE
->inactive()   // INACTIVE
->pending()    // PENDING
->completed()  // COMPLETED
->inProgress() // IN_PROGRESS
->draft()      // DRAFT
->published()  // PUBLISHED
->archived()   // ARCHIVED
```

### Time-Based Contexts

```php
->current()    // CURRENT
->past()       // PAST
->future()     // FUTURE
```

### Quiz-Specific Contexts

```php
->timed()           // TIMED
->unlimited()       // UNLIMITED
->limitedAttempts() // LIMITED_ATTEMPTS
->randomized()      // RANDOMIZED
->sequential()      // SEQUENTIAL
```

### Assignment-Specific Contexts

```php
->group()        // GROUP
->individual()   // INDIVIDUAL
->peerReview()   // PEER_REVIEW
->autoGraded()   // AUTO_GRADED
->manualGraded() // MANUAL_GRADED
```

### Course-Specific Contexts

```php
->prerequisite() // PREREQUISITE
->elective()     // ELECTIVE
->mandatory()    // MANDATORY
->online()       // ONLINE
->offline()      // OFFLINE
->hybrid()       // HYBRID
```

### User-Specific Contexts

```php
->verified()     // VERIFIED
->unverified()   // UNVERIFIED
->firstTime()    // FIRST_TIME
->returning()    // RETURNING
->guest()        // GUEST
```

### Submission-Specific Contexts

```php
->onTime()       // ON_TIME
->late()         // LATE
->early()        // EARLY
->resubmission() // RESUBMISSION
```

### Badge-Specific Contexts

```php
->achievement()  // ACHIEVEMENT
->participation() // PARTICIPATION
->completion()   // COMPLETION
->excellence()   // EXCELLENCE
```

### Media-Specific Contexts

```php
->video()        // VIDEO
->audio()        // AUDIO
->document()     // DOCUMENT
->image()        // IMAGE
->interactive()  // INTERACTIVE
```

### Attribute-Based Contexts

```php
->tag('advanced')     // TAG::advanced
->id('123')          // ID::123
->grade('A')         // GRADE::A
->level('beginner')  // LEVEL::beginner
```

## Common Usage Examples

### Quiz Permissions

```php
// Student can take a timed quiz
$permission = PermissionHelper::create()
    ->take()
    ->quiz()
    ->timed()
    ->build();
// Result: "TAKE_QUIZ_TIMED"

// Teacher can grade quiz with unlimited attempts
$permission = PermissionHelper::create()
    ->grade()
    ->quiz()
    ->unlimited()
    ->build();
// Result: "GRADE_QUIZ_UNLIMITED"
```

### Assignment Permissions

```php
// Student can submit individual assignment
$permission = PermissionHelper::create()
    ->submit()
    ->assignment()
    ->individual()
    ->build();
// Result: "SUBMIT_ASSIGNMENT_INDIVIDUAL"

// Teacher can review peer review assignments
$permission = PermissionHelper::create()
    ->review()
    ->assignment()
    ->peerReview()
    ->build();
// Result: "REVIEW_ASSIGNMENT_PEER_REVIEW"
```

### Course Permissions

```php
// Student can attend online course
$permission = PermissionHelper::create()
    ->attend()
    ->course()
    ->online()
    ->build();
// Result: "ATTEND_COURSE_ONLINE"

// Manager can manage mandatory courses
$permission = PermissionHelper::create()
    ->manage()
    ->course()
    ->mandatory()
    ->build();
// Result: "MANAGE_COURSE_MANDATORY"
```

### User Management Permissions

```php
// Admin can activate verified users
$permission = PermissionHelper::create()
    ->activate()
    ->user()
    ->verified()
    ->build();
// Result: "ACTIVATE_USER_VERIFIED"

// Manager can invite users to department
$permission = PermissionHelper::create()
    ->invite()
    ->user()
    ->department()
    ->build();
// Result: "INVITE_USER_DEPARTMENT"
```

### Badge Permissions

```php
// System can award achievement badges
$permission = PermissionHelper::create()
    ->award()
    ->badge()
    ->achievement()
    ->build();
// Result: "AWARD_BADGE_ACHIEVEMENT"

// Student can earn completion badges
$permission = PermissionHelper::create()
    ->earn()
    ->badge()
    ->completion()
    ->build();
// Result: "EARN_BADGE_COMPLETION"
```

### Media Permissions

```php
// Teacher can upload video content
$permission = PermissionHelper::create()
    ->upload()
    ->media()
    ->video()
    ->build();
// Result: "UPLOAD_MEDIA_VIDEO"

// Student can download document materials
$permission = PermissionHelper::create()
    ->download()
    ->media()
    ->document()
    ->build();
// Result: "DOWNLOAD_MEDIA_DOCUMENT"
```

### Report Permissions

```php
// Admin can generate analytics reports
$permission = PermissionHelper::create()
    ->generate()
    ->reports()
    ->admin()
    ->build();
// Result: "GENERATE_REPORTS_ADMIN"

// Manager can analyze department reports
$permission = PermissionHelper::create()
    ->analyze()
    ->reports()
    ->department()
    ->build();
// Result: "ANALYZE_REPORTS_DEPARTMENT"
```

## Advanced Usage with Attributes

```php
// Permission for specific course level
$permission = PermissionHelper::create()
    ->view()
    ->course()
    ->level('advanced')
    ->build();
// Result: "VIEW_COURSE_LEVEL::advanced"

// Permission for specific grade
$permission = PermissionHelper::create()
    ->update()
    ->submission()
    ->grade('A')
    ->build();
// Result: "UPDATE_SUBMISSION_GRADE::A"

// Permission for specific tag
$permission = PermissionHelper::create()
    ->manage()
    ->quiz()
    ->tag('final-exam')
    ->build();
// Result: "MANAGE_QUIZ_TAG::final-exam"
```

## Method Chaining Rules

1. **Verb first**: Always start with a verb method
2. **Noun second**: Follow with a noun method
3. **Context third**: End with a context method
4. **Attributes optional**: Use `withAttribute()` or attribute-based contexts for specific values
5. **Build last**: Always call `build()` to get the final permission string

## Error Handling

The helper will validate that all required components are set before building:

```php
try {
    $permission = PermissionHelper::create()
        ->view() // Missing noun and context
        ->build();
} catch (InvalidArgumentException $e) {
    // Handle validation error
}
```

## Integration with Laravel Gates

```php
// In a Policy class
public function takeQuiz(User $user, Quiz $quiz)
{
    $permission = PermissionHelper::create()
        ->take()
        ->quiz()
        ->student()
        ->build();
    
    return $user->hasPermissionTo($permission);
}

// In a Controller
public function show(Quiz $quiz)
{
    $permission = PermissionHelper::create()
        ->view()
        ->quiz()
        ->published()
        ->build();
    
    $this->authorize($permission, $quiz);
    
    return view('quiz.show', compact('quiz'));
}
```

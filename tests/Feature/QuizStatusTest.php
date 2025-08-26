<?php

namespace Tests\Feature;

use App\Enums\Status\QuizStatus;
use App\Enums\Status\QuizAttemptStatus;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Libs\Roles\RoleHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class QuizStatusTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;
    protected User $teacher;
    protected Course $course;
    protected Quiz $quiz;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Tạo users
        $this->student = User::factory()->create();
        $this->teacher = User::factory()->create();
        
        // Gán roles (giả sử có method để gán role)
        // $this->student->assignRole('student');
        // $this->teacher->assignRole('teacher');
        
        // Tạo course
        $this->course = Course::factory()->create([
            'status' => 'published'
        ]);
        
        // Enroll student vào course
        $this->course->users()->attach($this->student->id);
        
        // Tạo quiz
        $this->quiz = Quiz::factory()->create([
            'title' => 'Test Quiz',
            'status' => QuizStatus::PUBLISHED->value,
            'max_attempts' => 3,
            'time_limit_minutes' => 60
        ]);
        
        // Attach quiz to course
        $this->course->quizzes()->attach($this->quiz->id, [
            'start_at' => now()->subHour(),
            'end_at' => now()->addHour()
        ]);
    }

    /** @test */
    public function quiz_status_draft_should_not_be_accessible()
    {
        $this->quiz->update(['status' => QuizStatus::DRAFT->value]);
        
        $this->actingAs($this->student);
        
        // Test canTakeQuiz method
        $canTake = app('App\Filament\Pages\QuizTaking')->canTakeQuiz($this->quiz);
        $this->assertFalse($canTake, 'Student should not be able to take draft quiz');
        
        // Test getQuizStatus method
        $status = app('App\Filament\Pages\QuizTaking')->getQuizStatus($this->quiz);
        $this->assertEquals('not_published', $status['status']);
        $this->assertFalse($status['canTake']);
        $this->assertEquals('Quiz chưa được xuất bản', $status['message']);
    }

    /** @test */
    public function quiz_status_published_should_be_accessible()
    {
        $this->quiz->update(['status' => QuizStatus::PUBLISHED->value]);
        
        $this->actingAs($this->student);
        
        // Mock RoleHelper::isStudent to return true
        $this->mock(RoleHelper::class, function ($mock) {
            $mock->shouldReceive('isStudent')->andReturn(true);
        });
        
        // Test canTakeQuiz method
        $canTake = app('App\Filament\Pages\QuizTaking')->canTakeQuiz($this->quiz);
        $this->assertTrue($canTake, 'Student should be able to take published quiz');
    }

    /** @test */
    public function quiz_status_closed_should_not_be_accessible()
    {
        $this->quiz->update(['status' => QuizStatus::CLOSED->value]);
        
        $this->actingAs($this->student);
        
        // Test canTakeQuiz method
        $canTake = app('App\Filament\Pages\QuizTaking')->canTakeQuiz($this->quiz);
        $this->assertFalse($canTake, 'Student should not be able to take closed quiz');
        
        // Test getQuizStatus method
        $status = app('App\Filament\Pages\QuizTaking')->getQuizStatus($this->quiz);
        $this->assertEquals('not_published', $status['status']);
        $this->assertFalse($status['canTake']);
    }

    /** @test */
    public function quiz_status_archived_should_not_be_accessible()
    {
        $this->quiz->update(['status' => QuizStatus::ARCHIVED->value]);
        
        $this->actingAs($this->student);
        
        // Test canTakeQuiz method
        $canTake = app('App\Filament\Pages\QuizTaking')->canTakeQuiz($this->quiz);
        $this->assertFalse($canTake, 'Student should not be able to take archived quiz');
        
        // Test getQuizStatus method
        $status = app('App\Filament\Pages\QuizTaking')->getQuizStatus($this->quiz);
        $this->assertEquals('not_published', $status['status']);
        $this->assertFalse($status['canTake']);
    }

    /** @test */
    public function quiz_with_time_restrictions_should_respect_timing()
    {
        $this->quiz->update(['status' => QuizStatus::PUBLISHED->value]);
        
        // Set quiz time to future
        $this->course->quizzes()->updateExistingPivot($this->quiz->id, [
            'start_at' => now()->addHour(),
            'end_at' => now()->addHours(2)
        ]);
        
        $this->actingAs($this->student);
        
        // Mock RoleHelper::isStudent to return true
        $this->mock(RoleHelper::class, function ($mock) {
            $mock->shouldReceive('isStudent')->andReturn(true);
        });
        
        // Test canTakeQuiz method
        $canTake = app('App\Filament\Pages\QuizTaking')->canTakeQuiz($this->quiz);
        $this->assertFalse($canTake, 'Student should not be able to take quiz before start time');
    }

    /** @test */
    public function quiz_should_respect_max_attempts()
    {
        $this->quiz->update([
            'status' => QuizStatus::PUBLISHED->value,
            'max_attempts' => 2
        ]);
        
        $this->actingAs($this->student);
        
        // Create 2 completed attempts
        QuizAttempt::factory()->count(2)->create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'status' => QuizAttemptStatus::COMPLETED->value
        ]);
        
        // Mock RoleHelper::isStudent to return true
        $this->mock(RoleHelper::class, function ($mock) {
            $mock->shouldReceive('isStudent')->andReturn(true);
        });
        
        // Test remaining attempts
        $remainingAttempts = app('App\Filament\Pages\QuizTaking')->getQuizRemainingAttempts($this->quiz);
        $this->assertEquals(0, $remainingAttempts, 'Should have 0 remaining attempts');
        
        // Test canTakeQuiz method
        $canTake = app('App\Filament\Pages\QuizTaking')->canTakeQuiz($this->quiz);
        $this->assertFalse($canTake, 'Student should not be able to take quiz after max attempts reached');
    }

    /** @test */
    public function quiz_isactive_attribute_should_work_correctly()
    {
        // Test published quiz with valid timing
        $this->quiz->update(['status' => QuizStatus::PUBLISHED->value]);
        $this->course->quizzes()->updateExistingPivot($this->quiz->id, [
            'start_at' => now()->subHour(),
            'end_at' => now()->addHour()
        ]);
        
        $this->assertTrue($this->quiz->fresh()->isActive, 'Published quiz with valid timing should be active');
        
        // Test draft quiz
        $this->quiz->update(['status' => QuizStatus::DRAFT->value]);
        $this->assertFalse($this->quiz->fresh()->isActive, 'Draft quiz should not be active');
        
        // Test published quiz with expired timing
        $this->quiz->update(['status' => QuizStatus::PUBLISHED->value]);
        $this->course->quizzes()->updateExistingPivot($this->quiz->id, [
            'start_at' => now()->subHours(2),
            'end_at' => now()->subHour()
        ]);
        
        $this->assertFalse($this->quiz->fresh()->isActive, 'Published quiz with expired timing should not be active');
    }

    /** @test */
    public function quiz_status_enum_should_have_correct_values()
    {
        $this->assertEquals('draft', QuizStatus::DRAFT->value);
        $this->assertEquals('published', QuizStatus::PUBLISHED->value);
        $this->assertEquals('closed', QuizStatus::CLOSED->value);
        $this->assertEquals('archived', QuizStatus::ARCHIVED->value);
    }

    /** @test */
    public function quiz_status_descriptions_should_be_available()
    {
        $this->assertNotNull(QuizStatus::DRAFT->getDescription());
        $this->assertNotNull(QuizStatus::PUBLISHED->getDescription());
        $this->assertNotNull(QuizStatus::CLOSED->getDescription());
        $this->assertNotNull(QuizStatus::ARCHIVED->getDescription());
    }

    /** @test */
    public function non_student_should_not_be_able_to_take_quiz()
    {
        $this->quiz->update(['status' => QuizStatus::PUBLISHED->value]);
        
        $this->actingAs($this->teacher);
        
        // Mock RoleHelper::isStudent to return false for teacher
        $this->mock(RoleHelper::class, function ($mock) {
            $mock->shouldReceive('isStudent')->andReturn(false);
        });
        
        // Test canTakeQuiz method
        $canTake = app('App\Filament\Pages\QuizTaking')->canTakeQuiz($this->quiz);
        $this->assertFalse($canTake, 'Non-student should not be able to take quiz');
    }

    /** @test */
    public function student_not_enrolled_in_course_should_not_access_quiz()
    {
        $this->quiz->update(['status' => QuizStatus::PUBLISHED->value]);
        
        // Create another student not enrolled in course
        $unenrolledStudent = User::factory()->create();
        $this->actingAs($unenrolledStudent);
        
        // Mock RoleHelper::isStudent to return true
        $this->mock(RoleHelper::class, function ($mock) {
            $mock->shouldReceive('isStudent')->andReturn(true);
        });
        
        // Test canTakeQuiz method
        $canTake = app('App\Filament\Pages\QuizTaking')->canTakeQuiz($this->quiz);
        $this->assertFalse($canTake, 'Unenrolled student should not be able to take quiz');
        
        // Test getQuizStatus method
        $status = app('App\Filament\Pages\QuizTaking')->getQuizStatus($this->quiz);
        $this->assertEquals('no_access', $status['status']);
        $this->assertFalse($status['canTake']);
        $this->assertEquals('Bạn chưa đăng ký khóa học này', $status['message']);
    }
}
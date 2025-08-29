<?php

namespace Tests\Feature;

use App\Enums\Status\QuizAttemptStatus;
use App\Enums\Status\QuizStatus;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizAttemptStatusTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;

    protected Course $course;

    protected Quiz $quiz;

    protected function setUp(): void
    {
        parent::setUp();

        // Tạo student
        $this->student = User::factory()->create();

        // Tạo course
        $this->course = Course::factory()->create([
            'status' => 'published',
        ]);

        // Enroll student vào course
        $this->course->users()->attach($this->student->id);

        // Tạo quiz
        $this->quiz = Quiz::factory()->create([
            'title' => 'Test Quiz for Attempts',
            'status' => QuizStatus::PUBLISHED->value,
            'max_attempts' => 5,
            'time_limit_minutes' => 30,
        ]);

        // Attach quiz to course
        $this->course->quizzes()->attach($this->quiz->id, [
            'start_at' => now()->subHour(),
            'end_at' => now()->addHour(),
        ]);
    }

    /** @test */
    public function quiz_attempt_status_started_should_be_created_correctly()
    {
        $attempt = QuizAttempt::factory()->create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'status' => QuizAttemptStatus::STARTED->value,
            'start_at' => now(),
            'end_at' => null,
        ]);

        $this->assertEquals(QuizAttemptStatus::STARTED->value, $attempt->status);
        $this->assertNotNull($attempt->start_at);
        $this->assertNull($attempt->end_at);
    }

    /** @test */
    public function quiz_attempt_status_in_progress_should_allow_continuation()
    {
        $attempt = QuizAttempt::factory()->create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'status' => QuizAttemptStatus::IN_PROGRESS->value,
            'start_at' => now()->subMinutes(10),
            'end_at' => null,
        ]);

        $this->actingAs($this->student);

        // Test getQuizStatus với in_progress attempt
        $status = app('App\Filament\Pages\MyQuiz')->getQuizStatus($this->quiz);

        $this->assertEquals('in_progress', $status['status']);
        $this->assertEquals('Tiếp tục làm bài', $status['label']);
        $this->assertTrue($status['canTake']);
        $this->assertFalse($status['canViewResults']);
        $this->assertEquals($attempt->id, $status['attempt']->id);
    }

    /** @test */
    public function quiz_attempt_status_completed_should_show_results()
    {
        $attempt = QuizAttempt::factory()->create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'status' => QuizAttemptStatus::COMPLETED->value,
            'start_at' => now()->subHour(),
            'end_at' => now()->subMinutes(30),
            'score' => 85.5,
        ]);

        $this->assertEquals(QuizAttemptStatus::COMPLETED->value, $attempt->status);
        $this->assertNotNull($attempt->start_at);
        $this->assertNotNull($attempt->end_at);
        $this->assertEquals(85.5, $attempt->score);
    }

    /** @test */
    public function quiz_attempt_status_abandoned_should_not_count_as_completed()
    {
        $attempt = QuizAttempt::factory()->create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'status' => QuizAttemptStatus::ABANDONED->value,
            'start_at' => now()->subHour(),
            'end_at' => null,
            'score' => null,
        ]);

        $this->actingAs($this->student);

        // Test remaining attempts - abandoned attempt should not reduce remaining attempts
        $remainingAttempts = app('App\Filament\Pages\QuizTaking')->getQuizRemainingAttempts($this->quiz);
        $this->assertEquals(5, $remainingAttempts, 'Abandoned attempt should not reduce remaining attempts');
    }

    /** @test */
    public function quiz_attempt_status_graded_should_have_final_score()
    {
        $attempt = QuizAttempt::factory()->create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'status' => QuizAttemptStatus::GRADED->value,
            'start_at' => now()->subHours(2),
            'end_at' => now()->subHour(),
            'score' => 92.0,
            'graded_at' => now()->subMinutes(30),
        ]);

        $this->assertEquals(QuizAttemptStatus::GRADED->value, $attempt->status);
        $this->assertEquals(92.0, $attempt->score);
        $this->assertNotNull($attempt->graded_at);
    }

    /** @test */
    public function quiz_attempt_status_transitions_should_be_logical()
    {
        // Tạo attempt với status STARTED
        $attempt = QuizAttempt::factory()->create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'status' => QuizAttemptStatus::STARTED->value,
            'start_at' => now(),
        ]);

        // Chuyển sang IN_PROGRESS
        $attempt->update([
            'status' => QuizAttemptStatus::IN_PROGRESS->value,
        ]);
        $this->assertEquals(QuizAttemptStatus::IN_PROGRESS->value, $attempt->fresh()->status);

        // Chuyển sang COMPLETED
        $attempt->update([
            'status' => QuizAttemptStatus::COMPLETED->value,
            'end_at' => now(),
            'score' => 78.5,
        ]);
        $this->assertEquals(QuizAttemptStatus::COMPLETED->value, $attempt->fresh()->status);

        // Chuyển sang GRADED
        $attempt->update([
            'status' => QuizAttemptStatus::GRADED->value,
            'graded_at' => now(),
        ]);
        $this->assertEquals(QuizAttemptStatus::GRADED->value, $attempt->fresh()->status);
    }

    /** @test */
    public function multiple_attempts_should_track_status_correctly()
    {
        // Tạo nhiều attempts với status khác nhau
        $attempt1 = QuizAttempt::factory()->create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'status' => QuizAttemptStatus::COMPLETED->value,
            'start_at' => now()->subDays(2),
            'end_at' => now()->subDays(2)->addMinutes(30),
            'score' => 70.0,
        ]);

        $attempt2 = QuizAttempt::factory()->create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'status' => QuizAttemptStatus::ABANDONED->value,
            'start_at' => now()->subDay(),
            'end_at' => null,
        ]);

        $attempt3 = QuizAttempt::factory()->create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'status' => QuizAttemptStatus::IN_PROGRESS->value,
            'start_at' => now()->subMinutes(10),
            'end_at' => null,
        ]);

        $this->actingAs($this->student);

        // Test getQuizStatus - should return in_progress status
        $status = app('App\Filament\Pages\MyQuiz')->getQuizStatus($this->quiz);
        $this->assertEquals('in_progress', $status['status']);
        $this->assertEquals($attempt3->id, $status['attempt']->id);

        // Test remaining attempts - should count only completed/graded attempts
        $remainingAttempts = app('App\Filament\Pages\QuizTaking')->getQuizRemainingAttempts($this->quiz);
        $this->assertEquals(4, $remainingAttempts, 'Should have 4 remaining attempts (5 max - 1 completed)');
    }

    /** @test */
    public function quiz_attempt_status_enum_should_have_correct_values()
    {
        $this->assertEquals('started', QuizAttemptStatus::STARTED->value);
        $this->assertEquals('in_progress', QuizAttemptStatus::IN_PROGRESS->value);
        $this->assertEquals('completed', QuizAttemptStatus::COMPLETED->value);
        $this->assertEquals('abandoned', QuizAttemptStatus::ABANDONED->value);
        $this->assertEquals('graded', QuizAttemptStatus::GRADED->value);
    }

    /** @test */
    public function quiz_attempt_status_descriptions_should_be_available()
    {
        $this->assertNotNull(QuizAttemptStatus::STARTED->getDescription());
        $this->assertNotNull(QuizAttemptStatus::IN_PROGRESS->getDescription());
        $this->assertNotNull(QuizAttemptStatus::COMPLETED->getDescription());
        $this->assertNotNull(QuizAttemptStatus::ABANDONED->getDescription());
        $this->assertNotNull(QuizAttemptStatus::GRADED->getDescription());
    }

    /** @test */
    public function quiz_attempt_factory_should_create_correct_statuses()
    {
        // Test factory methods
        $completedAttempt = QuizAttempt::factory()->completed()->create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
        ]);
        $this->assertEquals(QuizAttemptStatus::COMPLETED->value, $completedAttempt->status);
        $this->assertNotNull($completedAttempt->end_at);

        $inProgressAttempt = QuizAttempt::factory()->inProgress()->create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
        ]);
        $this->assertEquals(QuizAttemptStatus::IN_PROGRESS->value, $inProgressAttempt->status);

        $abandonedAttempt = QuizAttempt::factory()->abandoned()->create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
        ]);
        $this->assertEquals(QuizAttemptStatus::ABANDONED->value, $abandonedAttempt->status);
    }
}

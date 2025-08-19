<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add foreign keys for courses table
        Schema::table('courses', function (Blueprint $table) {
            $table->foreign('teacher_id', 'fk_courses_teacher_id')
                ->references('id')->on('users')
                ->onDelete('restrict');

        });


        // Add foreign keys for course_user table
        Schema::table('course_user', function (Blueprint $table) {
            $table->foreign('course_id', 'fk_course_user_course_id')
                ->references('id')->on('courses')
                ->onDelete('cascade');

            $table->foreign('user_id', 'fk_course_user_user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });

        // Add foreign keys for course_assignments table
        Schema::table('course_assignments', function (Blueprint $table) {
            $table->foreign('course_id', 'fk_course_assignments_course_id')
                ->references('id')->on('courses')
                ->onDelete('cascade');

            $table->foreign('assignment_id', 'fk_course_assignments_assignment_id')
                ->references('id')->on('assignments')
                ->onDelete('cascade');
        });

        // Add foreign keys for course_quizzes table
        Schema::table('course_quizzes', function (Blueprint $table) {
            $table->foreign('course_id', 'fk_course_quizzes_course_id')
                ->references('id')->on('courses')
                ->onDelete('cascade');

            $table->foreign('quiz_id', 'fk_course_quizzes_quiz_id')
                ->references('id')->on('quizzes')
                ->onDelete('cascade');
        });

        // Add foreign keys for submissions table
        Schema::table('submissions', function (Blueprint $table) {
            $table->foreign('assignment_id', 'fk_submissions_assignment_id')
                ->references('id')->on('assignments')
                ->onDelete('cascade');

            $table->foreign('student_id', 'fk_submissions_student_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });

        // Add foreign keys for quiz_questions table
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->foreign('quiz_id', 'fk_quiz_questions_quiz_id')
                ->references('id')->on('quizzes')
                ->onDelete('cascade');

            $table->foreign('question_id', 'fk_quiz_questions_question_id')
                ->references('id')->on('questions')
                ->onDelete('cascade');
        });

        // Add foreign keys for answer_choices table
        Schema::table('answer_choices', function (Blueprint $table) {
            $table->foreign('question_id', 'fk_answer_choices_question_id')
                ->references('id')->on('questions')
                ->onDelete('cascade');
        });

        // Add foreign keys for quiz_attempts table
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->foreign('quiz_id', 'fk_quiz_attempts_quiz_id')
                ->references('id')->on('quizzes')
                ->onDelete('cascade');

            $table->foreign('student_id', 'fk_quiz_attempts_student_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });

        // Add foreign keys for student_quiz_answers table
        Schema::table('student_quiz_answers', function (Blueprint $table) {
            $table->foreign('quiz_attempt_id', 'fk_student_quiz_answers_quiz_attempt_id')
                ->references('id')->on('quiz_attempts')
                ->onDelete('cascade');

            $table->foreign('question_id', 'fk_student_quiz_answers_question_id')
                ->references('id')->on('questions')
                ->onDelete('cascade');

            $table->foreign('answer_choice_id', 'fk_student_quiz_answers_answer_choice_id')
                ->references('id')->on('answer_choices')
                ->onDelete('set null');
        });

        // Add foreign keys for badge_has_conditions table
        Schema::table('badge_has_conditions', function (Blueprint $table) {
            $table->foreign('badge_id', 'fk_badge_has_conditions_badge_id')
                ->references('id')->on('badges')
                ->onDelete('cascade');

            $table->foreign('badge_condition_id', 'fk_badge_has_conditions_badge_condition_id')
                ->references('id')->on('badge_conditions')
                ->onDelete('cascade');
        });

        // Add foreign keys for user_badges table
        Schema::table('user_badges', function (Blueprint $table) {
            $table->foreign('user_id', 'fk_user_badges_user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('badge_id', 'fk_user_badges_badge_id')
                ->references('id')->on('badges')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys in reverse order
        Schema::table('user_badges', function (Blueprint $table) {
            $table->dropForeign('fk_user_badges_user_id');
            $table->dropForeign('fk_user_badges_badge_id');
        });

        Schema::table('badge_has_conditions', function (Blueprint $table) {
            $table->dropForeign('fk_badge_has_conditions_badge_id');
            $table->dropForeign('fk_badge_has_conditions_badge_condition_id');
        });

        Schema::table('student_quiz_answers', function (Blueprint $table) {
            $table->dropForeign('fk_student_quiz_answers_quiz_attempt_id');
            $table->dropForeign('fk_student_quiz_answers_question_id');
            $table->dropForeign('fk_student_quiz_answers_answer_choice_id');
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropForeign('fk_quiz_attempts_quiz_id');
            $table->dropForeign('fk_quiz_attempts_student_id');
        });

        Schema::table('answer_choices', function (Blueprint $table) {
            $table->dropForeign('fk_answer_choices_question_id');
        });

        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->dropForeign('fk_quiz_questions_quiz_id');
            $table->dropForeign('fk_quiz_questions_question_id');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->dropForeign('fk_submissions_assignment_id');
            $table->dropForeign('fk_submissions_student_id');
        });

        Schema::table('course_quizzes', function (Blueprint $table) {
            $table->dropForeign('fk_course_quizzes_course_id');
            $table->dropForeign('fk_course_quizzes_quiz_id');
        });

        Schema::table('course_assignments', function (Blueprint $table) {
            $table->dropForeign('fk_course_assignments_course_id');
            $table->dropForeign('fk_course_assignments_assignment_id');
        });

        Schema::table('course_managers', function (Blueprint $table) {
            $table->dropForeign('fk_course_managers_course_id');
            $table->dropForeign('fk_course_managers_manager_id');
        });

        Schema::table('course_students', function (Blueprint $table) {
            $table->dropForeign('fk_course_students_course_id');
            $table->dropForeign('fk_course_students_student_id');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign('fk_courses_teacher_id');
        });
    }
};

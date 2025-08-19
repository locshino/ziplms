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
        Schema::create('course_user', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->uuid('user_id');
            // $table->string('role'); // Đã loại bỏ, phân vai qua spatie permission
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['course_id', 'user_id']);
            $table->index('course_id');
            $table->index('user_id');
            // $table->index('role'); // Đã loại bỏ
        });

        Schema::create('course_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->uuid('assignment_id');

            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_submission_at')->nullable();
            $table->timestamp('start_grading_at')->nullable();
            $table->timestamp('end_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['course_id', 'assignment_id']);
            $table->index('course_id');
            $table->index('assignment_id');
        });

        Schema::create('course_quizzes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->uuid('quiz_id');

            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['course_id', 'quiz_id']);
            $table->index('course_id');
            $table->index('quiz_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_quizzes');
        Schema::dropIfExists('course_assignments');
        Schema::dropIfExists('course_managers');
        Schema::dropIfExists('course_students');
    }
};

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
        Schema::create('exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id')->nullable(); // FK to courses table
            $table->uuid('lecture_id')->nullable(); // FK to lectures table
            $table->json('title'); // Multilingual title.
            $table->json('description')->nullable(); // Multilingual description.

            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();

            $table->unsignedInteger('duration_minutes')->nullable();
            $table->unsignedInteger('max_attempts')->default(1);
            $table->decimal('passing_score')->nullable();

            $table->boolean('shuffle_questions')->default(false);
            $table->boolean('shuffle_answers')->default(false);

            $table->string('show_results_after')->default(App\Enums\ExamShowResultsType::MANUAL->value);
            $table->uuid('created_by')->nullable(); // FK to users table
            $table->string('status')->default(App\States\Active::class);

            $table->timestamp('results_visible_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('course_id');
            $table->index('lecture_id');
            $table->index('created_by');
            $table->index('start_time');

            $table->foreign('course_id')->references('id')
                ->on('courses')->onDelete('set null');
            $table->foreign('lecture_id')->references('id')
                ->on('lectures')->onDelete('set null');
            $table->foreign('created_by')->references('id')
                ->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};

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
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('quiz_id');
            $table->uuid('student_id');

            $table->decimal('points', 8, 2)->nullable();
            $table->json('answers')->nullable(); // Support capture answer choices when quiz is done

            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();

            $table->string('status');

            $table->timestamps();
            $table->softDeletes();

            $table->index('quiz_id');
            $table->index('student_id');
            $table->index('status');
        });

        Schema::create('student_quiz_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('quiz_attempt_id');
            $table->uuid('question_id');
            $table->uuid('answer_choice_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('quiz_attempt_id');
            $table->index('question_id');
            $table->index('answer_choice_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_quiz_answers');
        Schema::dropIfExists('quiz_attempts');
    }
};

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
        Schema::create('exam_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('exam_attempt_id'); // FK to exam_attempts table
            $table->uuid('exam_question_id'); // FK to exam_questions table
            $table->uuid('question_id'); // FK to questions table
            $table->text('answer_text')->nullable(); // Cho câu tự luận.
            $table->json('chosen_option_ids')->nullable(); // Lưu ID các lựa chọn đã chọn (cho MCQ nhiều đáp án).
            $table->uuid('selected_choice_id')->nullable(); // FK to question_choices table
            $table->decimal('points_earned')->nullable(); // Điểm đạt được cho câu trả lời này.
            $table->boolean('is_correct')->nullable(); // Đánh dấu đúng/sai.
            $table->json('teacher_feedback')->nullable(); // Phản hồi của giáo viên (hỗ trợ đa ngôn ngữ).
            $table->timestamp('graded_at')->nullable();
            $table->uuid('graded_by')->nullable(); // FK to users table
            $table->timestamps();

            $table->foreign('exam_attempt_id')->references('id')->on('exam_attempts')->onDelete('cascade');
            $table->foreign('exam_question_id')->references('id')->on('exam_questions')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->foreign('selected_choice_id')->references('id')->on('question_choices')->onDelete('set null');
            $table->foreign('graded_by')->references('id')->on('users')->onDelete('set null');
            $table->unique(['exam_attempt_id', 'exam_question_id'], 'exam_answers_attempt_question_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_answers');
    }
};

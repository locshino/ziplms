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
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('exam_id'); // FK to exams table
            $table->uuid('question_id'); // FK to questions table
            $table->decimal('points')->default(1.00);
            $table->unsignedInteger('question_order')->default(0); // Thứ tự câu hỏi.

            $table->timestamps();
            $table->softDeletes();

            $table->index('exam_id');
            $table->index('question_id');
            $table->unique([
                'exam_id',
                'question_id',
            ], 'exam_questions_exam_id_question_id_unique');

            $table->foreign('exam_id')->references('id')
                ->on('exams')->onDelete('cascade');
            $table->foreign('question_id')->references('id')
                ->on('questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};

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
         Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organization_id')->nullable(); // FK to organizations table
            $table->json('question_text'); // Nội dung câu hỏi (hỗ trợ đa ngôn ngữ).
            $table->string('question_type'); // Ví dụ: 'mcq_single', 'mcq_multiple', 'true_false', 'essay', 'fill_blank'.
            $table->json('explanation')->nullable(); // Giải thích (hỗ trợ đa ngôn ngữ).
            $table->uuid('created_by')->nullable(); // FK to users table
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->index('question_type', 'questions_question_type_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};

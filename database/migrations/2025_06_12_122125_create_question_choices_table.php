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
        Schema::create('question_choices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('question_id'); // FK to questions table
            $table->json('choice_text'); // Nội dung lựa chọn (hỗ trợ đa ngôn ngữ).
            $table->boolean('is_correct')->default(false);
            $table->unsignedInteger('choice_order')->default(0);
            $table->timestamps();
            $table->index('question_id');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_choices');
    }
};

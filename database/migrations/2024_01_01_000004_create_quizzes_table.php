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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('title');
            $table->text('description')->nullable();

            $table->unsignedInteger('max_attempts')->nullable();
            $table->boolean('is_single_session')->default(false);
            $table->unsignedInteger('time_limit_minutes')->nullable();

            $table->string('status');

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            // NOTE: quizzes has tags (use spatie tags)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};

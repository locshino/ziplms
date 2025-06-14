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
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assignment_id'); // FK to assignments table
            $table->uuid('user_id'); // FK to users table
            $table->text('submission_text')->nullable(); // Nếu nộp dạng text.
            $table->timestamp('submission_date')->useCurrent();
            // $table->string('status', 50)->nullable(); // (Managed by spatie/laravel-model-states)
            // Files will be handled by spatie/laravel-medialibrary
            $table->timestamps();
            $table->index('assignment_id');
            $table->index('user_id');
            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};

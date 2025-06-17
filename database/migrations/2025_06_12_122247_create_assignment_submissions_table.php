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
            $table->string('status')->default(App\States\Active::class);
            // Files will be handled by spatie/laravel-medialibrary

            $table->timestamp('submission_date')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            $table->index('assignment_id');
            $table->index('user_id');

            $table->foreign('assignment_id')->references('id')
                ->on('assignments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');
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

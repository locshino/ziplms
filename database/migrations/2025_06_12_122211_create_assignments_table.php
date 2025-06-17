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
        Schema::create('assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id'); // FK to courses table
            $table->json('title'); // Tiêu đề (hỗ trợ đa ngôn ngữ).
            $table->json('instructions')->nullable(); // Hướng dẫn (hỗ trợ đa ngôn ngữ).
            // $table->string('assignment_type'); // Managed by plugin filament-spatie-tags 
            $table->decimal('max_score')->nullable();
            $table->boolean('allow_late_submissions')->default(false);
            $table->uuid('created_by')->nullable(); // FK to users table
            $table->string('status')->default(App\States\Active::class);

            $table->dateTime('due_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('course_id');
            $table->index('created_by');
            $table->index('due_date');

            $table->foreign('course_id')->references('id')
                ->on('courses')->onDelete('cascade');
            $table->foreign('created_by')->references('id')
                ->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};

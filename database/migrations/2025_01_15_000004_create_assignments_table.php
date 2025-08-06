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
            $table->uuid('course_id');
            $table->string('title');
            $table->text('instructions')->nullable();
            $table->decimal('max_points', 8, 2)->default(100.00);
            $table->decimal('late_penalty_percentage', 5, 2)->nullable();
            $table->timestamp('start_at');
            $table->timestamp('due_at');
            $table->timestamp('grading_at');
            $table->timestamp('end_at');
            $table->timestamps();
            $table->softDeletes();
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

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
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('exam_id'); // FK to exams table
            $table->uuid('user_id'); // FK to users table
            $table->unsignedInteger('attempt_number')->default(1);
            $table->decimal('score')->nullable();
            $table->unsignedInteger('time_spent_seconds')->nullable(); // Thời gian đã làm.
            $table->json('feedback')->nullable();
            $table->string('status')->default(App\States\Active::class);

            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('exam_id');
            $table->index('user_id');

            $table->foreign('exam_id')->references('id')
                ->on('exams')->onDelete('cascade');
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
    }
};

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
        Schema::create('submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assignment_id');
            $table->uuid('student_id');

            $table->text('content')->nullable();
            $table->string('status');
            $table->timestamp('submitted_at')->nullable();

            $table->uuid('graded_by')->nullable();
            $table->decimal('points', 8, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->timestamp('graded_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('assignment_id');
            $table->index('student_id');
            $table->index('status');
            // NOTE: submissions has submission_documents (use spatie media)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};

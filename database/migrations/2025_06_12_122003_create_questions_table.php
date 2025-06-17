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
            $table->json('question_text'); // Multilingual question text.
            // $table->string('question_type');  // Managed by plugin filament-spatie-tags
            $table->json('explanation')->nullable(); // Multilingual explanation for the question.
            $table->uuid('created_by')->nullable(); // FK to users table

            $table->timestamps();
            $table->softDeletes();

            $table->index('organization_id');
            $table->index('created_by');
            $table->index('question_type');

            $table->foreign('organization_id')->references('id')
                ->on('organizations')->onDelete('set null');
            $table->foreign('created_by')->references('id')
                ->on('users')->onDelete('set null');
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

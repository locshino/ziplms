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
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organization_id')->nullable(); // FK to organizations table
            $table->json('title'); // Tiêu đề (hỗ trợ đa ngôn ngữ).
            $table->json('description')->nullable(); // Mô tả (hỗ trợ đa ngôn ngữ).
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->string('location')->nullable();
            $table->uuid('created_by')->nullable(); // FK to users table
            // $table->string('status', 50)->nullable(); // (Managed by spatie/laravel-model-states)

            $table->timestamps();
            $table->softDeletes();

            $table->index('organization_id');
            $table->index('created_by');
            $table->index('start_time', 'ev_start_time_idx');

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
        Schema::dropIfExists('events');
    }
};

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
        Schema::create('lecture_materials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lecture_id'); // FK to lectures table
            $table->json('name'); // Multilingual name
            $table->json('description')->nullable(); // Multilingual description
            $table->uuid('uploaded_by')->nullable(); // FK to users table
            $table->json('video_links')->nullable();
            // $table->string('attachments')->nullable(); // Files will be handled by spatie/laravel-medialibrary and associated with this model

            $table->timestamps();
            $table->softDeletes();

            $table->index('lecture_id');
            $table->index('uploaded_by');

            $table->foreign('lecture_id')->references('id')
                ->on('lectures')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id')
                ->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecture_materials');
    }
};

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

            $table->string('title');
            $table->text('description')->nullable();

            $table->decimal('max_points', 8, 2)->default(10.00);
            $table->unsignedInteger('max_attempts')->nullable();

            $table->string('status');

            $table->timestamps();
            $table->softDeletes();

            // NOTE: assignments has assignment_documents, tags (use spatie media and spatie tags)
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

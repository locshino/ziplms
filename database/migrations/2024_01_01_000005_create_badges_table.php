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
        Schema::create('badges', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->string('status');

            $table->timestamps();
            $table->softDeletes();

            // NOTE: badges has badge_covers, tags (use spatie media and spatie tags)
        });

        Schema::create('badge_conditions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('title');
            $table->text('description')->nullable();

            $table->string('condition_type');
            $table->json('condition_data')->nullable();

            $table->string('status');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badge_conditions');
        Schema::dropIfExists('badges');
    }
};

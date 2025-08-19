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
        Schema::create('badge_has_conditions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('badge_id');
            $table->uuid('badge_condition_id');

            $table->string('status');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['badge_id', 'badge_condition_id']);
            $table->index('badge_id');
            $table->index('badge_condition_id');
            $table->index('status');
        });

        Schema::create('user_badges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('badge_id');

            $table->timestamp('earned_at')->nullable();
            $table->string('status');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'badge_id']);
            $table->index('user_id');
            $table->index('badge_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('badge_has_conditions');
    }
};

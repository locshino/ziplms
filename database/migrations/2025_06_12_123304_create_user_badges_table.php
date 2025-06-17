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
        Schema::create('user_badges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id'); // FK to users table
            $table->uuid('badge_id'); // FK to badges table

            $table->timestamp('awarded_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('badge_id');
            $table->unique([
                'user_id',
                'badge_id',
            ], 'user_badges_user_badge_unique');

            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');
            $table->foreign('badge_id')->references('id')
                ->on('badges')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_badges');
    }
};

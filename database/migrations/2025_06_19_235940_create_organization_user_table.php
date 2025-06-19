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
        Schema::create('organization_users', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Hoặc bạn có thể bỏ qua id và dùng khóa chính kết hợp
            $table->uuid('organization_id');
            $table->uuid('user_id');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('organization_id');
            $table->index('user_id');

            // Foreign key constraints
            $table->foreign('organization_id')->references('id')
                ->on('organizations')->onDelete('cascade');
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->unique([
                'organization_id',
                'user_id',
            ], 'organization_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_user');
    }
};

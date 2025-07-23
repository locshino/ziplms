<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            // Xóa các cột không còn sử dụng
            if (Schema::hasColumn('organizations', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('organizations', 'phone_number')) {
                $table->dropColumn('phone_number');
            }
            if (Schema::hasColumn('organizations', 'settings')) {
                $table->dropColumn('settings');
            }
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            // Thêm lại các cột nếu cần rollback
            $table->string('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->json('settings')->nullable();
        });
    }
};

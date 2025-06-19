<?php

use App\States\Progress\Pending;
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
        Schema::create('batches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organization_id')->nullable(); // FK to organizations table
            $table->uuid('uploaded_by_user_id'); // FK to users table
            $table->string('original_file_name'); // Tên file gốc.
            $table->string('storage_path'); // Đường dẫn lưu trữ file trên server.
            $table->unsignedInteger('total_rows'); // Tổng số dòng trong file.
            $table->unsignedInteger('processed_rows')->default(0); // Số dòng đã xử lý.
            $table->unsignedInteger('successful_imports')->default(0); // Số lượng nhập thành công.
            $table->unsignedInteger('failed_imports')->default(0); // Số lượng nhập thất bại.
            $table->json('error_log')->nullable(); // Log các lỗi chi tiết.
            $table->string('error_report_path')->nullable(); // Đường dẫn lưu trữ báo cáo lỗi.
            $table->string('status')->default(Pending::class);

            $table->timestamps();
            $table->softDeletes();

            $table->index('organization_id');
            $table->index('uploaded_by_user_id');

            $table->foreign('organization_id')->references('id')
                ->on('organizations')->onDelete('set null');
            $table->foreign('uploaded_by_user_id')->references('id')
                ->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};

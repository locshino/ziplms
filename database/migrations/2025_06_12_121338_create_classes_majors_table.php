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
          Schema::create('classes_majors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organization_id'); // FK to organizations table
            $table->json('name'); // Tên (Lớp 10A1, Ngành CNTT, Khoa Toán) (hỗ trợ đa ngôn ngữ).
            $table->string('code')->nullable(); // Mã định danh (nếu có).
            $table->json('description')->nullable(); // Mô tả (hỗ trợ đa ngôn ngữ).
            $table->string('type'); // Ví dụ: 'class', 'major', 'department', 'grade_level'.
            $table->uuid('parent_id')->nullable(); // FK to classes_majors table (self-referencing)
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('classes_majors')->onDelete('cascade');
            $table->index('type', 'classes_majors_type_index');
            $table->index('code', 'classes_majors_code_index');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes_majors');
    }
};

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
        Schema::create('school_campus_course_curriculums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campus_course_id')->nullable()->constrained('school_campus_courses')->nullOnDelete();
            $table->foreignId('semester_type_id')->nullable()->constrained('list_references')->nullOnDelete();
            $table->string('years');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_delete')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_campus_course_curriculums');
    }
};

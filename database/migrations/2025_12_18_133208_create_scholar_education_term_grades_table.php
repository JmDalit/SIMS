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
        Schema::create('scholar_education_term_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acadterm_id')->nullable()->constrained('scholar_education_terms')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('school_campus_course_subjects')->nullOnDelete();
            $table->string('grades')->nullable();
            $table->string('status')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_delete')->default(false);
            $table->string('verified_by')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholar_education_term_grades');
    }
};

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

        Schema::create('scholar_term_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')
                ->nullable()
                ->constrained('scholars')
                ->nullOnDelete();
            $table->foreignId('scholar_school_id')
                ->nullable()
                ->constrained('scholar_school_infos')
                ->nullOnDelete();
            $table->foreignId('term_id')->nullable()->constrained('list_references')->nullOnDelete();
            $table->foreignId('level_id')->nullable()->constrained('list_references')->nullOnDelete();
            $table->string('academic_year')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('scholar_school_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('term_record_id')->nullable()->constrained('scholar_term_records')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('school_campus_course_subjects')->nullOnDelete();
            $table->string('remarks')->nullable();
            $table->foreignId('grades_id')->nullable()->constrained('list_statuses')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholar_school_grades');
    }
};

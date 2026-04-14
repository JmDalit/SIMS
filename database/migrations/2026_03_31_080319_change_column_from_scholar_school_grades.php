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
        Schema::table('scholar_school_grades', function (Blueprint $table) {
            $table->dropForeign(['grade_id']);
            $table->dropForeign(['subject_id']);

            // Drop old column
            $table->dropColumn('subject_id');
            $table->dropColumn('grade_id');
            $table->unsignedBigInteger('grade_id')->nullable()->after('subject_id');
            $table->foreign('grade_id')->references('id')->on('school_campus_grades')->onDelete('set null');
            $table->unsignedBigInteger('subject_id')->nullable()->after('subject_id');
            $table->foreign('subject_id')->references('id')->on('school_campus_course_curriculum_subjects')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholar_school_grades', function (Blueprint $table) {
            //
        });
    }
};

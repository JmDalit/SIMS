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
        Schema::table('scholar_school_infos', function (Blueprint $table) {
            $table->foreignId('curriculum_id')
                ->nullable()
                ->constrained('school_campus_course_curriculums')
                ->nullOnDelete()
                ->after('campus_course_id');
            $table->dropColumn('graduated_year');
        });
        // Schema::table('scholars', function (Blueprint $table) {
        //     $table->string('graduated_year')->nullable()->after('award_year');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholar_school_infos', function (Blueprint $table) {
            //
        });
    }
};

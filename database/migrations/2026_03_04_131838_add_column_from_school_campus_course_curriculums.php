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
        Schema::table('school_campus_course_curriculums', function (Blueprint $table) {
            $table->boolean('is_duplicated')
                ->default(false)
                ->after('created_by')
                ->comment('Indicates if this curriculum is duplicated from another');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_campus_course_curriculums', function (Blueprint $table) {
            //
        });
    }
};

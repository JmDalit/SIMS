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
        Schema::create('scholar_school_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->nullable()->constrained('scholars')->cascadeOnDelete();
            $table->string('campus_id')->nullable()->constrained('school_campuses')->cascadeOnDelete();
            $table->foreignId('campus_course_id')->nullable()->constrained('school_campus_courses')->cascadeOnDelete();
            $table->string('award_year')->nullable();
            $table->string('graduated_year')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholar_school_infos');
    }
};

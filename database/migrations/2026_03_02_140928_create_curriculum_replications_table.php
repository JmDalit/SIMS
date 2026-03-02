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
        Schema::create('curriculum_replications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_id')
                ->nullable()
                ->constrained('school_campus_course_curriculums')
                ->nullOnDelete();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculum_replications');
    }
};

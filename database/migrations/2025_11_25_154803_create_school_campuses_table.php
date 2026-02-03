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
        Schema::create('school_campuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('generated_name')->nullable();
            $table->foreignId('school_id')->constrained('schools')->onDelete('set null');
            $table->foreignId('agency_id')->constrained('list_agencies')->onDelete('set null');
            $table->foreignId('term_id')->constrained('list_references')->onDelete('set null');
            $table->foreignId('grading_id')->constrained('list_references')->onDelete('set null');
            $table->boolean('is_main')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_delete')->default(false);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_campuses');
    }
};

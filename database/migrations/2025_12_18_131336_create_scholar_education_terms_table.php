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
        Schema::create('scholar_education_terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('education_id')->nullable()->constrained('scholar_educations')->nullOnDelete();
            $table->foreignId('term_id')->nullable()->constrained('list_references')->nullOnDelete();
            $table->string('school_year');
            $table->date('start_date');
            $table->date('end_date');
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
        Schema::dropIfExists('scholar_education_terms');
    }
};

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
        Schema::create('scholar_uploaded_files', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('filepath');
            $table->string('region_office')->nullable();
            $table->string('created_by')->nullable();
            $table->string('validated_by')->nullable();
            $table->dateTime('validated_at')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholar_uploaded_files');
    }
};

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
        Schema::create('list_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('icon')->nullable();
            $table->string('type')->nullable();
            $table->foreignId('color_id')->nullable()->constrained('list_colors')->onDelete('set null');
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
        Schema::dropIfExists('list_statuses');
    }
};

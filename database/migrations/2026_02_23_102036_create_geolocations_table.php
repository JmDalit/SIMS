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
        Schema::create('geolocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('geolocations')
                ->cascadeOnDelete();
            $table->string('psgc_code')->unique();
            $table->string('name');
            $table->string('old_name')->nullable();
            $table->string('district')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('type');
            $table->timestamps();
            $table->softDeletes();
            $table->index('parent_id');
            $table->index('type');
            $table->index(['parent_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geolocations');
    }
};

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

            $table->dropColumn('campus_id');
            $table->foreignId('campus_id')->nullable()->constrained('school_campuses')->nullOnDelete();
        });
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

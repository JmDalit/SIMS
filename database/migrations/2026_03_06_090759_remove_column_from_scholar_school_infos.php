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
            $table->dropForeign(['term_id']);
            $table->dropColumn('term_id');
            $table->foreignId('term_record_id')
                ->nullable()
                ->constrained('scholar_term_records')
                ->nullOnDelete();
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

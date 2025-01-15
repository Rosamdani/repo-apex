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
        Schema::table('soal_tryouts', function (Blueprint $table) {
            $table->dropForeign(['kompetensi_id']);

            $table->dropColumn('kompetensi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soal_tryouts', function (Blueprint $table) {
            $table->string('kompetensi_id', 36);

            $table->foreign('kompetensi_id')->references('id')->on('kompetensi_tryouts')->onDelete('cascade');
        });
    }
};

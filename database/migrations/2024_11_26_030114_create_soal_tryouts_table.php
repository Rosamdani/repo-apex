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
        Schema::create('soal_tryouts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tryout_id', 36);
            $table->string('bidang_id', 36);
            $table->string('kompetensi_id', 36);
            $table->foreign('tryout_id')->references('id')->on('tryouts')->onDelete('cascade');
            $table->foreign('bidang_id')->references('id')->on('bidang_tryouts')->onDelete('cascade');
            $table->foreign('kompetensi_id')->references('id')->on('kompetensi_tryouts')->onDelete('cascade');
            $table->integer('nomor');
            $table->text('soal');
            $table->text('pilihan_a');
            $table->text('pilihan_b');
            $table->text('pilihan_c');
            $table->text('pilihan_d');
            $table->text('pilihan_e');
            $table->text('jawaban');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_tryouts');
    }
};

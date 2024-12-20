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
        Schema::create('user_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('soal_id', 36);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreign('soal_id')->references('id')->on('soal_tryouts')->onDelete('cascade');
            $table->text('jawaban')->nullable();
            $table->enum('status', ['dijawab', 'belum_dijawab', 'ragu-ragu', 'tidak_dijawab']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_answers');
    }
};

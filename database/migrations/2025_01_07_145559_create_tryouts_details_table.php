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
        Schema::create('tryouts_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tryout_id', 36);
            $table->foreign('tryout_id')->references('id')->on('tryouts')->onDelete('cascade');
            $table->text('deskripsi')->nullable();
            $table->string('harga')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tryouts_details');
    }
};

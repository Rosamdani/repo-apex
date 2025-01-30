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
        Schema::create('paket_tryouts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('paket');
            $table->string('image')->nullable();
            $table->string('url')->nullable();
            $table->integer('harga')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['active', 'nonaktif'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_tryouts');
    }
};
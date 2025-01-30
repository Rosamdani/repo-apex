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
        Schema::create('tryout_has_pakets', function (Blueprint $table) {
            $table->string('tryout_id', 36);
            $table->foreign('tryout_id')->references('id')->on('tryouts')->onDelete('cascade');
            $table->string('paket_id', 36);
            $table->foreign('paket_id')->references('id')->on('paket_tryouts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tryout_has_pakets');
    }
};

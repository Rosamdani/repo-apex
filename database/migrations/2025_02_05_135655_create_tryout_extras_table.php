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
        Schema::create('tryout_extras', function (Blueprint $table) {
            $table->id();
            $table->morphs('extraable');
            $table->string('type');
            $table->string('title')->nullable();
            $table->string('data');
            $table->json('display_on')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tryout_extras');
    }
};

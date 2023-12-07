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
        Schema::create('duel_details', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('round');
            $table->integer('your_points');
            $table->integer('opponent_points');
            $table->tinyInteger('your_card_id');
            $table->tinyInteger('opponent_card_id');
            $table->unsignedBigInteger('duel_id');
            $table->timestamps();

            $table->foreign('duel_id')->references('id')->on('duels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duel_details');
    }
};

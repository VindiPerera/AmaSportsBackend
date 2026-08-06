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
        // Every sport a player has added. A player can add more than one
        // sport (e.g. Cricket + Hockey). `completed` once the sport's full
        // form (Cricket/Hockey) has been submitted; "coming soon" sports
        // stay `placeholder` forever in this phase since there's no form.
        Schema::create('player_sports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sport_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['placeholder', 'completed'])->default('placeholder');
            $table->timestamps();

            $table->unique(['player_id', 'sport_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_sports');
    }
};

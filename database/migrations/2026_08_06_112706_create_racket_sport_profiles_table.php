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
        // Shared table set for Tennis / Badminton / Table Tennis — one form
        // structure reused across all three (spec Phase 2 §B3). `sport_id`
        // disambiguates which of the three a row belongs to; a player can
        // have a separate profile per sport (e.g. both Tennis and
        // Badminton), so uniqueness is per (player, sport), not per player.
        Schema::create('racket_sport_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sport_id')->constrained();
            $table->date('born')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('height')->nullable();
            $table->enum('dominant_hand', ['right', 'left'])->nullable();
            $table->string('weight')->nullable();
            $table->string('current_ranking')->nullable();
            $table->string('college_university')->nullable();
            $table->timestamps();

            $table->unique(['player_id', 'sport_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('racket_sport_profiles');
    }
};

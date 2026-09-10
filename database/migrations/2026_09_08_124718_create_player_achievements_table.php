<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One row per player per achievement they've crossed the threshold for.
 * `posted_at` is the whole point of this being separate from just "unlocked"
 * — the player is notified (see AchievementService::evaluateForPlayer, run
 * after a Cricket profile save and whenever the achievements list loads) but
 * nothing shows on their Profile/Home until they choose to post it.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->foreignId('achievement_id')->constrained()->cascadeOnDelete();
            $table->timestamp('unlocked_at');
            $table->timestamp('posted_at')->nullable();
            // The player's metric value at the moment it unlocked (e.g. 105
            // when the threshold was 100) — shown alongside the badge so
            // "Score 100 runs" reads as "You scored 105 runs!".
            $table->unsignedInteger('achieved_value');
            $table->timestamps();

            $table->unique(['player_id', 'achievement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_achievements');
    }
};

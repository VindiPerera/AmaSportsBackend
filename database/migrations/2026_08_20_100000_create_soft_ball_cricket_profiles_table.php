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
        Schema::create('soft_ball_cricket_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->unique()->constrained()->cascadeOnDelete();
            $table->date('born')->nullable();
            // Auto-calculated from `born` on the client, but stored as
            // entered since the player is allowed to manually override it.
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('batting_style')->nullable();
            $table->string('bowling_style')->nullable();
            $table->string('playing_role')->nullable();
            $table->string('height')->nullable();
            $table->string('college_university')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soft_ball_cricket_profiles');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * A logo image for the School/College/University affiliation of a player
     * per sport. Stored per sport so that each discipline can have its own
     * school/college logo.
     */
    public function up(): void
    {
        Schema::create('player_college_logos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sport_id')->constrained()->cascadeOnDelete();
            $table->string('logo_path');
            $table->timestamps();

            $table->unique(['player_id', 'sport_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_college_logos');
    }
};

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
        // Goalkeeper columns (clean sheets / goals conceded) are always
        // present but nullable — one shared row shape for every position,
        // outfield players just leave them blank (client-confirmed).
        Schema::create('football_career_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('football_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('format_id')->constrained();
            $table->foreignId('age_category_id')->constrained();
            $table->foreignId('match_category_id')->constrained();
            $table->unsignedInteger('matches')->nullable();
            $table->unsignedInteger('win')->nullable();
            $table->unsignedInteger('lost')->nullable();
            $table->unsignedInteger('goals')->nullable();
            $table->unsignedInteger('assists')->nullable();
            $table->unsignedInteger('defensive_actions')->nullable();
            $table->unsignedInteger('goalkeeper_clean_sheets')->nullable();
            $table->unsignedInteger('goalkeeper_goals_conceded')->nullable();
            $table->unsignedInteger('yellow_card')->nullable();
            $table->unsignedInteger('red_card')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('football_career_stats');
    }
};

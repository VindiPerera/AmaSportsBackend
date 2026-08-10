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
        Schema::create('football_recent_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('football_profile_id')->constrained()->cascadeOnDelete();
            $table->date('match_date')->nullable();
            $table->string('opponent')->nullable();
            $table->string('venue')->nullable();
            $table->boolean('win')->default(false);
            $table->boolean('lost')->default(false);
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
        Schema::dropIfExists('football_recent_matches');
    }
};

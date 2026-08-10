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
        // `category` holds which of the form's three tables a row belongs
        // to (Single / Double / Mix Double) — one shared table instead of
        // three near-identical ones (spec Phase 2 §B3).
        Schema::create('racket_sport_career_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('racket_sport_profile_id')->constrained()->cascadeOnDelete();
            $table->enum('category', ['single', 'double', 'mix_double']);
            $table->foreignId('format_id')->constrained();
            $table->foreignId('age_category_id')->constrained();
            $table->foreignId('match_category_id')->constrained();
            $table->unsignedInteger('matches')->nullable();
            $table->unsignedInteger('win')->nullable();
            $table->unsignedInteger('lost')->nullable();
            $table->unsignedInteger('set_win')->nullable();
            $table->unsignedInteger('set_lost')->nullable();
            $table->unsignedInteger('quarter_final')->nullable();
            $table->unsignedInteger('semi_final')->nullable();
            $table->unsignedInteger('third_place')->nullable();
            $table->unsignedInteger('second_place')->nullable();
            $table->unsignedInteger('champion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('racket_sport_career_stats');
    }
};

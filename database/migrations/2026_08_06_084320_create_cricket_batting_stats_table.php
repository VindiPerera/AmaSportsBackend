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
        // Repeatable "Career Status — Batting & Fielding" rows, one per
        // Format/Age/Category combination the player adds (spec 6.2).
        Schema::create('cricket_batting_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cricket_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('format_id')->constrained();
            $table->foreignId('age_category_id')->constrained();
            $table->foreignId('match_category_id')->constrained();
            $table->foreignId('cricket_match_type_id')->nullable()->constrained();
            $table->unsignedInteger('matches')->nullable();
            $table->unsignedInteger('won')->nullable();
            $table->unsignedInteger('lost')->nullable();
            $table->unsignedInteger('innings')->nullable();
            $table->unsignedInteger('not_out')->nullable();
            $table->unsignedInteger('runs')->nullable();
            // Can include a trailing "*" for not-out (e.g. "114*").
            $table->string('hs')->nullable();
            $table->decimal('average', 8, 2)->nullable();
            $table->unsignedInteger('best')->nullable();
            $table->decimal('sr', 8, 2)->nullable();
            $table->unsignedInteger('hundreds')->nullable();
            $table->unsignedInteger('fifties')->nullable();
            $table->unsignedInteger('fours')->nullable();
            $table->unsignedInteger('sixes')->nullable();
            $table->unsignedInteger('catches')->nullable();
            $table->unsignedInteger('stumpings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cricket_batting_stats');
    }
};

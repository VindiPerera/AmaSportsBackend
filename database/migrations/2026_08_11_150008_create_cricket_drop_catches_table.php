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
        // Repeatable "Drop Catches" rows (Phase 7 spec §2) — a player can
        // log any number of drop-catch events, each with its own Field
        // Position + How-to-Drop. Sibling to cricket_batting_stats /
        // cricket_bowling_stats / cricket_recent_matches: same
        // Format/Age/Category context columns, same delete-and-recreate
        // pattern in CricketProfileController::update().
        Schema::create('cricket_drop_catches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cricket_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('format_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('age_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('match_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('field_position_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('drop_reason_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cricket_drop_catches');
    }
};

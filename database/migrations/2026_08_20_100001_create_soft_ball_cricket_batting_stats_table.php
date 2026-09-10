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
        // Repeatable "Batting Career Stats" rows — flat, unlike Cricket's
        // Format/Age/Category-keyed table, since the reference sheet for
        // this sport doesn't break stats down that way.
        Schema::create('soft_ball_cricket_batting_stats', function (Blueprint $table) {
            $table->id();
            // Explicit short index name — the default auto-generated name
            // exceeds MySQL's 64-char identifier limit for this table name.
            $table->foreignId('soft_ball_cricket_profile_id')
                ->constrained(indexName: 'sbc_batting_stats_profile_id_foreign')
                ->cascadeOnDelete();
            $table->unsignedInteger('matches')->nullable();
            $table->unsignedInteger('runs')->nullable();
            $table->unsignedInteger('innings')->nullable();
            // Can include a trailing "*" for not-out (e.g. "114*").
            $table->string('highest')->nullable();
            $table->unsignedInteger('not_out')->nullable();
            $table->unsignedInteger('hundreds')->nullable();
            $table->unsignedInteger('fifties')->nullable();
            $table->unsignedInteger('sixes')->nullable();
            $table->unsignedInteger('fours')->nullable();
            $table->unsignedInteger('catches')->nullable();
            $table->unsignedInteger('stumpings')->nullable();
            $table->unsignedInteger('won')->nullable();
            $table->unsignedInteger('lost')->nullable();
            $table->unsignedInteger('tied')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soft_ball_cricket_batting_stats');
    }
};

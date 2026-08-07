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
        // Beach volleyball is best-of-3 (spec §D2) — only 3 set columns,
        // unlike indoor Volleyball's 5. FK named explicitly — see
        // beach_volleyball_career_stats migration for why.
        Schema::create('beach_volleyball_recent_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beach_volleyball_profile_id');
            $table->foreign('beach_volleyball_profile_id', 'bv_recent_matches_profile_fk')
                ->references('id')->on('beach_volleyball_profiles')->cascadeOnDelete();
            $table->date('match_date')->nullable();
            $table->string('opponent')->nullable();
            $table->string('venue')->nullable();
            $table->boolean('win')->default(false);
            $table->boolean('lost')->default(false);
            $table->string('set_1')->nullable();
            $table->string('set_2')->nullable();
            $table->string('set_3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beach_volleyball_recent_matches');
    }
};

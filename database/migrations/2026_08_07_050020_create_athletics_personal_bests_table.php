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
        // One row per event the player selected in Overview's multi-select
        // "Events" field, each with its own "Personal Best" value (spec
        // §C1). `personal_best` is a free-text string since the unit varies
        // by event (seconds, minutes, metres).
        Schema::create('athletics_personal_bests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('athletics_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('athletics_event_id')->constrained();
            $table->string('personal_best')->nullable();
            $table->timestamps();

            $table->unique(['athletics_profile_id', 'athletics_event_id'], 'athletics_personal_bests_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('athletics_personal_bests');
    }
};

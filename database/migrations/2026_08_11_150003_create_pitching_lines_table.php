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
        // Cricket bowling "Pitching Line" lookup — the longer, 10-item list
        // (Phase 7 spec §5) used for both the entry dropdown and the
        // per-category breakdown, per the user's decision to use one
        // authoritative list everywhere rather than the wireframe's two
        // inconsistent lists.
        Schema::create('pitching_lines', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pitching_lines');
    }
};

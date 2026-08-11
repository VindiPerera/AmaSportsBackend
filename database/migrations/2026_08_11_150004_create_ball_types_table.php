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
        // Cricket bowling "Ball Type" lookup, including "Other" as a real
        // selectable option (Phase 7 spec §5 — the wireframe's Report Page
        // showed "Other" but the Filling Page dropdown didn't offer it).
        Schema::create('ball_types', function (Blueprint $table) {
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
        Schema::dropIfExists('ball_types');
    }
};

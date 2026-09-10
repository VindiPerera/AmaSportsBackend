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
        // Cricket-only "Division" list — only offered for a handful of
        // Categories (U12...U19, see CareerStatAddModal's
        // DIVISION_ELIGIBLE_CATEGORIES); every other Category has no
        // Division at all. Its own table for the same reason as
        // cricket_categories — not the shared `formats` lookup other sports
        // use for their own "Format" field.
        Schema::create('cricket_divisions', function (Blueprint $table) {
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
        Schema::dropIfExists('cricket_divisions');
    }
};

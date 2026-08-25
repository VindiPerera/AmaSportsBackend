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
        // Cricket-only "Category" list for the Batting/Bowling Career Stats
        // "Add New Stat" flow's Category+Division picker (see
        // CareerStatAddModal on the mobile app). Deliberately its own table
        // rather than reusing the shared `age_categories` lookup — this list
        // mixes age groups (U12...U24) with competition levels (Div i,
        // District, Mercantile, ...) that are meaningless as an "age
        // category" for every other sport's profile form sharing that table.
        Schema::create('cricket_categories', function (Blueprint $table) {
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
        Schema::dropIfExists('cricket_categories');
    }
};

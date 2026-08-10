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
        // Ad-hoc match roster (ID Number / Name / Photo per side) entered by
        // the admin for a specific fixture. Deliberately NOT tied to
        // `players`/`users` — these participants don't have app accounts,
        // unlike the mobile app's own athlete-profile system.
        Schema::create('match_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
            $table->enum('side', ['home', 'away']);
            $table->string('id_number')->nullable();
            $table->string('full_name');
            $table->string('photo_url')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['match_id', 'side']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_players');
    }
};

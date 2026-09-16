<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cricket_recent_matches', function (Blueprint $table) {
            // Photo of the physical/official scoresheet for this match —
            // uploaded immediately via its own endpoint (see
            // CricketProfileController::uploadScoreSheet), then this URL
            // rides along with the rest of the row on the normal bulk save.
            // Kept on file only; nothing in the app displays it back.
            $table->string('score_sheet_url', 500)->nullable()->after('stumpings');
        });
    }

    public function down(): void
    {
        Schema::table('cricket_recent_matches', function (Blueprint $table) {
            $table->dropColumn('score_sheet_url');
        });
    }
};

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
        // Pay-per-match live streaming unlock (Phase 6 revision 2): the
        // admin running a match, or (revision 3) any player paying for "VIP
        // access", pays $5 to enable that match's YouTube embed for every
        // viewer — Live Score itself always stays free. Scoped to
        // `match_id` (not `player_id`) because access is per-match, not
        // account-wide, and automatically closes when the match finishes
        // (see GameMatch::hasActiveStreamAccess() and
        // Admin\LiveScoreController::finish()). `paid_by` records whichever
        // user (admin or player) actually paid.
        Schema::create('live_stream_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
            $table->foreignId('paid_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('paypal_order_id')->nullable()->index();
            $table->decimal('amount', 8, 2)->default(5.00);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'active', 'expired', 'cancelled'])->default('pending');
            $table->dateTime('purchased_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();

            $table->index(['match_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_stream_access');
    }
};

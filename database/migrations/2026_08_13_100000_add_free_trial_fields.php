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
        // Phase 8 (Free Trial) — one free 10-day trial on a player's
        // first-ever subscription, then the normal $10/year applies.
        Schema::table('subscriptions', function (Blueprint $table) {
            // True for the one subscription row created by
            // POST /subscriptions/start-trial — never charged, never has a
            // paypal_order_id. Everything else about it (status/starts_at/
            // expires_at) behaves exactly like a paid row so the existing
            // "check the latest row" gating logic doesn't need to
            // special-case it.
            $table->boolean('is_trial')->default(false)->after('status');
        });

        // Lives on `players`, not `subscriptions`, because it must survive
        // regardless of what happens to any subscription row: a player who
        // starts a trial and lets it lapse must never be able to start a
        // second one, and checking "does a trial subscription row exist"
        // would work today but is fragile against future changes (e.g. an
        // admin ever needing to delete a bad row). This is the single
        // source of truth for trial eligibility.
        Schema::table('players', function (Blueprint $table) {
            // Set once, the first (and only) time a player starts a trial.
            // NULL = still trial-eligible. Never cleared except by an admin
            // directly in the database — not exposed anywhere in the app UI.
            $table->dateTime('trial_used_at')->nullable()->after('photo_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('is_trial');
        });

        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('trial_used_at');
        });
    }
};

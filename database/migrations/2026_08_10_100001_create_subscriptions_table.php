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
        // One row per subscription purchase/renewal — never updated in place
        // to "extend" an expiry, always a fresh row (Phase 6 spec: "keep
        // history clean"). The player's current access is always resolved
        // from the most recent row (see Player::activeSubscription()).
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->string('paypal_order_id')->nullable()->index();
            $table->decimal('amount', 8, 2)->default(10.00);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'active', 'expired', 'cancelled'])->default('pending');
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();

            $table->index(['player_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};

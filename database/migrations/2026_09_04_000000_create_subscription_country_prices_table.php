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
        // Admin-configurable per-country override of Subscription::AMOUNT
        // (see SubscriptionCountryPrice::amountFor()) — a country with no
        // row here just falls back to the flat default price, same as
        // before this table existed. Currency is deliberately not per-row:
        // the product spec is "same currency (USD), different amount per
        // country", not multi-currency — see config('services.paypal.currency').
        Schema::create('subscription_country_prices', function (Blueprint $table) {
            $table->id();
            $table->string('country')->unique();
            $table->decimal('amount', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_country_prices');
    }
};

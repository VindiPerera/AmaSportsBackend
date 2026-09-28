<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Both payable tables share the exact same payment columns. */
    private const TABLES = ['subscriptions', 'live_stream_access'];

    /**
     * PayPal → PayHere. `paypal_order_id` becomes the gateway-neutral
     * `payment_order_id` (our own order_id sent to PayHere, e.g.
     * "SUB-12-AB3XQZ"), plus PayHere's own `payment_id` from notify_url
     * and which gateway a row went through — historical rows keep working
     * and stay labelled as PayPal in the admin payments list.
     */
    public function up(): void
    {
        foreach (self::TABLES as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->renameColumn('paypal_order_id', 'payment_order_id');
            });

            Schema::table($table, function (Blueprint $t) {
                $t->string('payment_gateway', 20)->default('payhere')->after('payment_order_id');
                $t->string('payment_reference')->nullable()->after('payment_gateway');
                $t->string('payment_method', 30)->nullable()->after('payment_reference');
            });

            DB::table($table)
                ->whereNotNull('payment_order_id')
                ->update(['payment_gateway' => 'paypal']);
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn(['payment_gateway', 'payment_reference', 'payment_method']);
            });

            Schema::table($table, function (Blueprint $t) {
                $t->renameColumn('payment_order_id', 'paypal_order_id');
            });
        }
    }
};

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Admin-configurable per-country subscription price (e.g. Sri Lanka $10,
 * India $6.99) — see Admin\SubscriptionPriceController for the CRUD screen
 * and amountFor() below for how the API resolves a player's actual price.
 * A country with no row here simply isn't configured yet; it's not the same
 * as "free" or "0" — amountFor() falls back to Subscription::AMOUNT.
 */
class SubscriptionCountryPrice extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'country',
        'amount',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    /**
     * The price a player from `$country` should be charged — the admin-set
     * override if one exists for that exact country name, otherwise the
     * flat default. `$country` is matched exactly against `Player.country`
     * (both free text drawn from the same fixed list on the mobile app —
     * see Frontend/src/constants/countries.ts), so an unrecognized or blank
     * country (never selected, or "Other") just gets the default price.
     */
    public static function amountFor(?string $country): float
    {
        if (! $country) {
            return Subscription::AMOUNT;
        }

        $row = static::query()->where('country', $country)->first();

        return $row ? (float) $row->amount : Subscription::AMOUNT;
    }

    /**
     * Every configured country's price, keyed by country name, for the
     * mobile app's country-selection screen to preview against before the
     * player has necessarily saved a country yet (see Api\SubscriptionController).
     *
     * @return array<string, float>
     */
    public static function allAsMap(): array
    {
        return static::query()
            ->orderBy('country')
            ->pluck('amount', 'country')
            ->map(fn ($amount) => (float) $amount)
            ->all();
    }
}

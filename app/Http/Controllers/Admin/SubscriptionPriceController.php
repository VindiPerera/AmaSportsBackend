<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubscriptionCountryPriceRequest;
use App\Models\Subscription;
use App\Models\SubscriptionCountryPrice;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Admin CRUD for per-country subscription pricing (see
 * SubscriptionCountryPrice::amountFor(), consumed by
 * Api\SubscriptionController). A country with no row here just charges the
 * flat default (Subscription::AMOUNT) — this screen only manages the
 * overrides, not a price for every country up front.
 */
class SubscriptionPriceController extends Controller
{
    public function index(): View
    {
        $prices = SubscriptionCountryPrice::query()->orderBy('country')->get();

        // Only offer countries that don't already have a price set — editing
        // an existing one happens via its own row's Edit action instead, so
        // "Add" never collides with a country already configured.
        $configuredCountries = $prices->pluck('country')->all();
        $availableCountries = collect(config('countries'))
            ->reject(fn ($country) => in_array($country, $configuredCountries, true))
            ->values();

        return view('admin.subscription-prices.index', [
            'prices' => $prices,
            'availableCountries' => $availableCountries,
            'defaultAmount' => Subscription::AMOUNT,
        ]);
    }

    public function create(): View
    {
        $configuredCountries = SubscriptionCountryPrice::query()->pluck('country')->all();
        $availableCountries = collect(config('countries'))
            ->reject(fn ($country) => in_array($country, $configuredCountries, true))
            ->values();

        return view('admin.subscription-prices.form', [
            'price' => null,
            'availableCountries' => $availableCountries,
        ]);
    }

    public function store(SubscriptionCountryPriceRequest $request): RedirectResponse
    {
        SubscriptionCountryPrice::create($request->validated());

        return redirect()
            ->route('admin.subscription-prices.index')
            ->with('success', 'Country price added.');
    }

    public function edit(SubscriptionCountryPrice $price): View
    {
        return view('admin.subscription-prices.form', [
            'price' => $price,
            // Editing keeps this row's own country selectable too (it's the
            // only option the form actually needs, since country is locked
            // once set — see the view).
            'availableCountries' => collect([$price->country]),
        ]);
    }

    public function update(SubscriptionCountryPriceRequest $request, SubscriptionCountryPrice $price): RedirectResponse
    {
        $price->update($request->validated());

        return redirect()
            ->route('admin.subscription-prices.index')
            ->with('success', 'Country price updated.');
    }

    public function destroy(SubscriptionCountryPrice $price): RedirectResponse
    {
        $price->delete();

        return redirect()
            ->route('admin.subscription-prices.index')
            ->with('success', 'Country price removed — that country now uses the default price again.');
    }
}

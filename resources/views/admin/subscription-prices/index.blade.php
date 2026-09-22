@extends('admin.layouts.app')

@section('title', 'Subscription Pricing')

@section('content')
    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-brand-charcoal tracking-tight">Subscription Pricing</h1>
            <p class="text-xs text-slate-500 mt-0.5">Set the annual subscription price per country — countries with no custom price charge the default.</p>
        </div>
        @if ($availableCountries->isNotEmpty())
            <x-button href="{{ route('admin.subscription-prices.create') }}" variant="primary" size="md">
                + Add Country Price
            </x-button>
        @endif
    </div>

    {{-- Default Price Notice --}}
    <div class="bg-amber-50/80 border border-amber-200 rounded-2xl px-5 py-3.5 mb-6 flex items-center gap-3">
        <span class="text-lg">💲</span>
        <p class="text-xs text-amber-900 font-medium">
            <span class="font-extrabold">Default platform price: ${{ number_format($defaultAmount, 2) }} / year.</span>
            Any country without an override entry in the table below is charged this amount.
        </p>
    </div>

    {{-- Prices Data Table Card --}}
    @if ($prices->isEmpty())
        <x-empty-state
            title="No custom country prices"
            message="No country-specific prices configured yet — every player is charged the ${{ number_format($defaultAmount, 2) }} default."
        >
            @if ($availableCountries->isNotEmpty())
                <x-button href="{{ route('admin.subscription-prices.create') }}" variant="primary" size="sm">
                    + Add Country Price
                </x-button>
            @endif
        </x-empty-state>
    @else
        <x-card class="p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-slate-50/80 border-b border-slate-100 text-slate-400 text-[10px] font-extrabold uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">Country</th>
                            <th class="px-5 py-3.5">Annual Price</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @foreach ($prices as $price)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-5 py-4 font-bold text-slate-800">{{ $price->country }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-extrabold bg-emerald-50 text-emerald-700">
                                        ${{ number_format($price->amount, 2) }} / year
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <x-button href="{{ route('admin.subscription-prices.edit', $price) }}" variant="secondary" size="sm">
                                            Edit
                                        </x-button>
                                        <form method="POST" action="{{ route('admin.subscription-prices.destroy', $price) }}"
                                              onsubmit="return confirm('Remove the custom price for {{ $price->country }}? It will fall back to the ${{ number_format($defaultAmount, 2) }} default.');">
                                            @csrf
                                            @method('DELETE')
                                            <x-button type="submit" variant="danger" size="sm">
                                                Remove
                                            </x-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>
    @endif
@endsection

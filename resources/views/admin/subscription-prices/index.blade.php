@extends('admin.layouts.app')

@section('title', 'Subscription Pricing')

@section('content')
    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Subscription Pricing</h1>
            <p class="text-sm text-slate-500 mt-0.5">Set the annual subscription price per country — a country with no price below charges the default.</p>
        </div>
        @if ($availableCountries->isNotEmpty())
            <a href="{{ route('admin.subscription-prices.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 text-sm font-bold shadow-lg shadow-blue-600/25 transition-all hover:scale-[1.02]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                + Add Country Price
            </a>
        @endif
    </div>

    {{-- Default Price Notice --}}
    <div class="bg-blue-50 border border-blue-200 rounded-2xl px-5 py-3.5 mb-6 flex items-center gap-3">
        <span class="text-lg">💲</span>
        <p class="text-sm text-blue-900">
            <span class="font-extrabold">Default price: ${{ number_format($defaultAmount, 2) }}/year.</span>
            Any country without an entry in the table below is charged this amount.
        </p>
    </div>

    {{-- Prices Data Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Country</th>
                        <th class="px-5 py-3.5">Annual Price</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($prices as $price)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-4 font-bold text-slate-800">{{ $price->country }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-extrabold bg-emerald-50 text-emerald-700">
                                    ${{ number_format($price->amount, 2) }} / year
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.subscription-prices.edit', $price) }}"
                                       class="px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.subscription-prices.destroy', $price) }}"
                                          onsubmit="return confirm('Remove the custom price for {{ $price->country }}? It will fall back to the ${{ number_format($defaultAmount, 2) }} default.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-10 text-center text-sm text-slate-500">
                                No country-specific prices yet — every player is charged the ${{ number_format($defaultAmount, 2) }} default.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

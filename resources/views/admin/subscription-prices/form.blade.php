@extends('admin.layouts.app')

@section('title', $price ? 'Edit Country Price' : 'Add Country Price')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.subscription-prices.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-brand-red mb-3 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Subscription Pricing
        </a>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $price ? 'Edit Country Price' : 'Add Country Price' }}</h1>
        <p class="text-xs font-semibold text-slate-500 mt-0.5">{{ $price ? 'Update the annual subscription price for this country.' : 'Pick a country and set what its players pay per year.' }}</p>
    </div>

    <form method="POST"
          action="{{ $price ? route('admin.subscription-prices.update', $price) : route('admin.subscription-prices.store') }}"
          class="max-w-lg">
        @csrf
        @if ($price) @method('PUT') @endif

        <x-card class="p-6 space-y-5">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Country <span class="text-brand-red">*</span></label>
                @if ($price)
                    {{-- Locked once set --}}
                    <input type="hidden" name="country" value="{{ $price->country }}">
                    <div class="w-full rounded-xl bg-slate-100 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700">
                        {{ $price->country }}
                    </div>
                @else
                    <select name="country" required class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
                        <option value="">Select country...</option>
                        @foreach ($availableCountries as $country)
                            <option value="{{ $country }}" @selected(old('country') === $country)>{{ $country }}</option>
                        @endforeach
                    </select>
                @endif
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Annual Price (USD) <span class="text-brand-red">*</span></label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">$</span>
                    <input type="number" name="amount" step="0.01" min="0" max="9999.99" placeholder="10.00"
                           value="{{ old('amount', $price?->amount) }}" required
                           class="w-full rounded-xl bg-slate-50 border border-slate-200 pl-8 pr-4 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
                </div>
                <p class="text-xs text-slate-500 mt-1.5">What a player who selects this country on the mobile app pays per year.</p>
            </div>
        </x-card>

        <div class="pt-6">
            <x-button type="submit" variant="primary" size="lg">
                {{ $price ? 'Save Price' : 'Add Country Price' }}
            </x-button>
        </div>
    </form>
@endsection

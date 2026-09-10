@extends('admin.layouts.app')

@section('title', $price ? 'Edit Country Price' : 'Add Country Price')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.subscription-prices.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-500 mb-2 inline-block">← Back to Subscription Pricing</a>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $price ? 'Edit Country Price' : 'Add Country Price' }}</h1>
        <p class="text-sm text-slate-500 mt-0.5">{{ $price ? 'Update the annual subscription price for this country.' : 'Pick a country and set what its players pay per year.' }}</p>
    </div>

    <form method="POST"
          action="{{ $price ? route('admin.subscription-prices.update', $price) : route('admin.subscription-prices.store') }}"
          class="max-w-lg">
        @csrf
        @if ($price) @method('PUT') @endif

        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-soft space-y-5">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Country <span class="text-red-500">*</span></label>
                @if ($price)
                    {{-- Locked once set — remove and re-add to target a different country, avoids ambiguity about which row is being edited. --}}
                    <input type="hidden" name="country" value="{{ $price->country }}">
                    <div class="w-full rounded-xl bg-slate-100 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600">
                        {{ $price->country }}
                    </div>
                @else
                    <select name="country" required class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        <option value="">Select country...</option>
                        @foreach ($availableCountries as $country)
                            <option value="{{ $country }}" @selected(old('country') === $country)>{{ $country }}</option>
                        @endforeach
                    </select>
                @endif
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Annual Price (USD) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">$</span>
                    <input type="number" name="amount" step="0.01" min="0" max="9999.99" placeholder="10.00"
                           value="{{ old('amount', $price?->amount) }}" required
                           class="w-full rounded-xl bg-slate-50 border border-slate-200 pl-8 pr-4 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <p class="text-xs text-slate-500 mt-1.5">What a player who selects this country on the mobile app pays per year.</p>
            </div>
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full sm:w-auto rounded-xl bg-blue-600 hover:bg-blue-500 text-white px-8 py-3.5 text-sm font-extrabold shadow-lg shadow-blue-600/30 hover:scale-[1.01] transition-all">
                {{ $price ? 'Save Price' : 'Add Country Price' }}
            </button>
        </div>
    </form>
@endsection

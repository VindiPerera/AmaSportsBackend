@extends('user.layouts.app')

@section('title', $match ? 'Edit Match' : 'Match Creation')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-brand-charcoal tracking-tight">
                {{ $match ? 'Edit Match Fixture' : 'Create New Match Fixture' }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Configure competing teams, venue, date-time, and tournament parameters.</p>
        </div>
        <x-button href="{{ route('user.matches.index') }}" variant="ghost" size="sm">
            ← Back to Matches
        </x-button>
    </div>

    @if ($errors->any())
        <x-alert type="error" title="Form Validation Error">
            <ul class="list-disc pl-5 space-y-1 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <form method="POST" action="{{ $match ? route('user.matches.update', $match) : route('user.matches.store') }}" class="space-y-6">
        @csrf
        @if($match)
            @method('PUT')
        @endif

        {{-- 1. Sport Selection --}}
        <x-card>
            <x-slot:header>
                <h3 class="text-sm font-bold text-slate-900">1. Sport Category</h3>
            </x-slot:header>

            <x-select name="sport_id" label="Select Sport" required>
                <option value="">Choose sport...</option>
                @foreach($sports as $s)
                    <option value="{{ $s->id }}" {{ old('sport_id', $match?->sport_id) == $s->id ? 'selected' : '' }}>
                        {{ $s->name }}
                    </option>
                @endforeach
            </x-select>
        </x-card>

        {{-- 2. Teams Configuration --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Home Team --}}
            <x-card class="space-y-4">
                <x-slot:header>
                    <h3 class="text-sm font-bold text-brand-red flex items-center gap-1.5">
                        <span>🛡️ Home Team / Side 1</span>
                    </h3>
                </x-slot:header>

                <x-input
                    name="home_team_name"
                    label="Team Name"
                    value="{{ $match?->homeTeam?->name }}"
                    required
                    placeholder="e.g. Royal Lions"
                />

                <x-input
                    name="home_team_school_academy"
                    label="School / Academy (Optional)"
                    value="{{ $match?->homeTeam?->school_academy }}"
                    placeholder="e.g. Royal Academy"
                />

                <x-input
                    name="home_team_club"
                    label="Club Name (Optional)"
                    value="{{ $match?->homeTeam?->club }}"
                    placeholder="e.g. Colombo Club"
                />
            </x-card>

            {{-- Away Team --}}
            <x-card class="space-y-4">
                <x-slot:header>
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-1.5">
                        <span>🛡️ Away Team / Side 2</span>
                    </h3>
                </x-slot:header>

                <x-input
                    name="away_team_name"
                    label="Team Name"
                    value="{{ $match?->awayTeam?->name }}"
                    required
                    placeholder="e.g. Trinity Warriors"
                />

                <x-input
                    name="away_team_school_academy"
                    label="School / Academy (Optional)"
                    value="{{ $match?->awayTeam?->school_academy }}"
                    placeholder="e.g. Trinity Academy"
                />

                <x-input
                    name="away_team_club"
                    label="Club Name (Optional)"
                    value="{{ $match?->awayTeam?->club }}"
                    placeholder="e.g. Kandy CC"
                />
            </x-card>
        </div>

        {{-- 3. Schedule, Venue & Settings --}}
        <x-card class="space-y-4">
            <x-slot:header>
                <h3 class="text-sm font-bold text-slate-900">3. Fixture Schedule &amp; Classification</h3>
            </x-slot:header>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-input
                    type="datetime-local"
                    name="scheduled_at"
                    label="Scheduled Date & Time"
                    value="{{ $match?->scheduled_at?->format('Y-m-d\TH:i') }}"
                    required
                />

                <x-input
                    name="venue"
                    label="Venue / Stadium"
                    value="{{ $match?->venue }}"
                    placeholder="e.g. Main Stadium Ground"
                />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-2">
                <x-select name="format_id" label="Match Format">
                    <option value="">Any Format</option>
                    @foreach($formats as $f)
                        <option value="{{ $f->id }}" {{ old('format_id', $match?->format_id) == $f->id ? 'selected' : '' }}>
                            {{ $f->name }}
                        </option>
                    @endforeach
                </x-select>

                <x-select name="age_category_id" label="Age Category">
                    <option value="">Any Age Group</option>
                    @foreach($ageCategories as $a)
                        <option value="{{ $a->id }}" {{ old('age_category_id', $match?->age_category_id) == $a->id ? 'selected' : '' }}>
                            {{ $a->name }}
                        </option>
                    @endforeach
                </x-select>

                <x-select name="match_category_id" label="Match Category">
                    <option value="">Any Category</option>
                    @foreach($matchCategories as $m)
                        <option value="{{ $m->id }}" {{ old('match_category_id', $match?->match_category_id) == $m->id ? 'selected' : '' }}>
                            {{ $m->name }}
                        </option>
                    @endforeach
                </x-select>

                <x-input
                    name="country"
                    label="Host Country"
                    value="{{ $match?->country }}"
                    placeholder="e.g. Sri Lanka"
                />
            </div>
        </x-card>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <x-button href="{{ route('user.matches.index') }}" variant="secondary" size="md">
                Cancel
            </x-button>
            <x-button type="submit" variant="primary" size="md">
                {{ $match ? 'Update Match Fixture' : 'Publish Match Fixture' }}
            </x-button>
        </div>

    </form>

</div>

@endsection

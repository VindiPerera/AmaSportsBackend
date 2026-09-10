@extends('user.layouts.app')

@section('title', $match ? 'Edit Match' : 'Match Creation')

@section('content')

<div style="max-width: 800px; margin: 0 auto;">

    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
        <div>
            <h1 style="font-weight: 900; font-size: 1.75rem; color: #fff; letter-spacing: -0.02em; margin-bottom: 0.25rem;">
                {{ $match ? 'Edit Match Fixture' : 'Match Creation' }}
            </h1>
            <p style="font-size: 0.875rem; color: rgba(148,163,184,0.75);">Set up team details, scheduled date, venue, and sport category.</p>
        </div>
        <a href="{{ route('user.matches.index') }}" style="color: rgba(148,163,184,0.8); font-weight: 700; font-size: 0.875rem; text-decoration: none;">
            ← Back to Matches
        </a>
    </div>

    @if ($errors->any())
        <div class="flash-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ $match ? route('user.matches.update', $match) : route('user.matches.store') }}" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.25rem; padding: 2rem; display: flex; flex-direction: column; gap: 1.5rem;">
        @csrf
        @if($match)
            @method('PUT')
        @endif

        {{-- Sport Selection --}}
        <div>
            <label style="display: block; font-size: 0.8125rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;" for="sport_id">
                Select Sport *
            </label>
            <select id="sport_id" name="sport_id" required class="form-input">
                <option value="" style="background: #0a0f1e;">Choose a sport...</option>
                @foreach($sports as $s)
                    <option value="{{ $s->id }}" {{ old('sport_id', $match?->sport_id) == $s->id ? 'selected' : '' }} style="background: #0a0f1e;">
                        {{ $s->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Home vs Away Teams --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
            {{-- Home Team --}}
            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 1rem; padding: 1.25rem;">
                <h3 style="font-weight: 800; font-size: 0.9375rem; color: #f59e0b; margin-bottom: 1rem;">Home Team / Side 1</h3>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: rgba(203,213,225,0.8); margin-bottom: 0.375rem;">Team Name *</label>
                        <input type="text" name="home_team_name" value="{{ old('home_team_name', $match?->homeTeam?->name) }}" required placeholder="e.g. Royal College" class="form-input">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: rgba(203,213,225,0.8); margin-bottom: 0.375rem;">School / Academy</label>
                        <input type="text" name="home_team_school_academy" value="{{ old('home_team_school_academy', $match?->homeTeam?->school_academy) }}" placeholder="e.g. Royal Academy" class="form-input">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: rgba(203,213,225,0.8); margin-bottom: 0.375rem;">Club</label>
                        <input type="text" name="home_team_club" value="{{ old('home_team_club', $match?->homeTeam?->club) }}" placeholder="e.g. Colombo SC" class="form-input">
                    </div>
                </div>
            </div>

            {{-- Away Team --}}
            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 1rem; padding: 1.25rem;">
                <h3 style="font-weight: 800; font-size: 0.9375rem; color: #818cf8; margin-bottom: 1rem;">Away Team / Side 2</h3>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: rgba(203,213,225,0.8); margin-bottom: 0.375rem;">Team Name *</label>
                        <input type="text" name="away_team_name" value="{{ old('away_team_name', $match?->awayTeam?->name) }}" required placeholder="e.g. S. Thomas' College" class="form-input">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: rgba(203,213,225,0.8); margin-bottom: 0.375rem;">School / Academy</label>
                        <input type="text" name="away_team_school_academy" value="{{ old('away_team_school_academy', $match?->awayTeam?->school_academy) }}" placeholder="e.g. STC Academy" class="form-input">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: rgba(203,213,225,0.8); margin-bottom: 0.375rem;">Club</label>
                        <input type="text" name="away_team_club" value="{{ old('away_team_club', $match?->awayTeam?->club) }}" placeholder="e.g. Mount Lavinia CC" class="form-input">
                    </div>
                </div>
            </div>
        </div>

        {{-- Schedule & Venue --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
            <div>
                <label style="display: block; font-size: 0.8125rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;" for="scheduled_at">
                    Scheduled Date &amp; Time *
                </label>
                <input id="scheduled_at" type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', $match?->scheduled_at?->format('Y-m-d\TH:i')) }}" required class="form-input">
            </div>

            <div>
                <label style="display: block; font-size: 0.8125rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;" for="venue">
                    Venue / Stadium
                </label>
                <input id="venue" type="text" name="venue" value="{{ old('venue', $match?->venue) }}" placeholder="e.g. SSC Grounds, Colombo" class="form-input">
            </div>
        </div>

        {{-- Match Details (Format, Age Category, Match Category, Country) --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 1rem;">
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: rgba(203,213,225,0.8); margin-bottom: 0.375rem;">Format</label>
                <select name="format_id" class="form-input">
                    <option value="" style="background: #0a0f1e;">Any Format</option>
                    @foreach($formats as $f)
                        <option value="{{ $f->id }}" {{ old('format_id', $match?->format_id) == $f->id ? 'selected' : '' }} style="background: #0a0f1e;">{{ $f->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: rgba(203,213,225,0.8); margin-bottom: 0.375rem;">Age Group</label>
                <select name="age_category_id" class="form-input">
                    <option value="" style="background: #0a0f1e;">Any Age Group</option>
                    @foreach($ageCategories as $a)
                        <option value="{{ $a->id }}" {{ old('age_category_id', $match?->age_category_id) == $a->id ? 'selected' : '' }} style="background: #0a0f1e;">{{ $a->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: rgba(203,213,225,0.8); margin-bottom: 0.375rem;">Category</label>
                <select name="match_category_id" class="form-input">
                    <option value="" style="background: #0a0f1e;">Any Category</option>
                    @foreach($matchCategories as $m)
                        <option value="{{ $m->id }}" {{ old('match_category_id', $match?->match_category_id) == $m->id ? 'selected' : '' }} style="background: #0a0f1e;">{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: rgba(203,213,225,0.8); margin-bottom: 0.375rem;">Country</label>
                <input type="text" name="country" value="{{ old('country', $match?->country) }}" placeholder="e.g. Sri Lanka" class="form-input">
            </div>
        </div>

        {{-- Submit --}}
        <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
            <a href="{{ route('user.matches.index') }}" style="padding: 0.75rem 1.5rem; border: 1px solid rgba(255,255,255,0.15); border-radius: 0.625rem; font-size: 0.875rem; font-weight: 700; color: rgba(255,255,255,0.8); text-decoration: none;">
                Cancel
            </a>
            <button type="submit" class="btn-primary" style="padding: 0.75rem 2rem; font-size: 0.875rem;">
                {{ $match ? 'Update Match' : 'Create Match Fixture' }}
            </button>
        </div>

    </form>

</div>

@endsection

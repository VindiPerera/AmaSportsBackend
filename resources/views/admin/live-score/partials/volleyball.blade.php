{{-- Volleyball scoreboard — fields match the `team_score` block of
     MultiSportLiveScore in sport-mobile/src/services/firebaseService.ts --}}
<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm text-gray-600 mb-1">{{ $match->homeTeam->name }} — total points</label>
            <input type="number" min="0" name="team_score[home_total]" value="{{ $score['home_total'] ?? '' }}" class="w-full rounded border-gray-300 text-center font-semibold">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">{{ $match->awayTeam->name }} — total points</label>
            <input type="number" min="0" name="team_score[away_total]" value="{{ $score['away_total'] ?? '' }}" class="w-full rounded border-gray-300 text-center font-semibold">
        </div>
    </div>

    <div>
        <p class="text-sm text-gray-600 mb-1">Set scores</p>
        <div class="grid grid-cols-4 gap-2 mb-2">
            @for ($i = 0; $i < 4; $i++)
                <input type="number" min="0" name="team_score[home_sets_or_breakdown][]"
                       value="{{ $score['home_sets_or_breakdown'][$i] ?? '' }}" placeholder="Set {{ $i + 1 }}"
                       class="rounded border-gray-300 text-center text-sm">
            @endfor
        </div>
        <div class="grid grid-cols-4 gap-2">
            @for ($i = 0; $i < 4; $i++)
                <input type="number" min="0" name="team_score[away_sets_or_breakdown][]"
                       value="{{ $score['away_sets_or_breakdown'][$i] ?? '' }}" placeholder="Set {{ $i + 1 }}"
                       class="rounded border-gray-300 text-center text-sm">
            @endfor
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm text-gray-600 mb-1">{{ $match->homeTeam->name }} — outs</label>
            <input type="number" min="0" name="team_score[outs_home]" value="{{ $score['outs_home'] ?? '' }}" class="w-full rounded border-gray-300 text-center">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">{{ $match->awayTeam->name }} — outs</label>
            <input type="number" min="0" name="team_score[outs_away]" value="{{ $score['outs_away'] ?? '' }}" class="w-full rounded border-gray-300 text-center">
        </div>
    </div>
</div>

{{-- Badminton scoreboard — fields match the `racket_scores` block of
     MultiSportLiveScore in sport-mobile/src/services/firebaseService.ts --}}
<div class="space-y-4">
    <div class="grid grid-cols-3 gap-3 text-center text-sm font-medium text-gray-500">
        <div></div>
        <div>{{ $match->homeTeam->name }}</div>
        <div>{{ $match->awayTeam->name }}</div>
    </div>

    @foreach ([1, 2, 3] as $set)
        <div class="grid grid-cols-3 gap-3 items-center">
            <label class="text-sm text-gray-600">Set {{ $set }}</label>
            <input type="number" min="0" name="racket_scores[set{{ $set }}_home]" value="{{ $score['set' . $set . '_home'] ?? '' }}" class="rounded border-gray-300 text-center">
            <input type="number" min="0" name="racket_scores[set{{ $set }}_away]" value="{{ $score['set' . $set . '_away'] ?? '' }}" class="rounded border-gray-300 text-center">
        </div>
    @endforeach

    <div class="grid grid-cols-3 gap-3 items-center border-t border-gray-100 pt-4">
        <label class="text-sm text-gray-600 font-medium">Current points</label>
        <input type="number" min="0" name="racket_scores[points_home]" value="{{ $score['points_home'] ?? '' }}" class="rounded border-gray-300 text-center font-semibold">
        <input type="number" min="0" name="racket_scores[points_away]" value="{{ $score['points_away'] ?? '' }}" class="rounded border-gray-300 text-center font-semibold">
    </div>

    <div>
        <label class="block text-sm text-gray-600 mb-1">Current set</label>
        <select name="racket_scores[current_set]" class="rounded border-gray-300 text-sm">
            @foreach ([1, 2, 3] as $set)
                <option value="{{ $set }}" @selected(($score['current_set'] ?? 1) == $set)>Set {{ $set }}</option>
            @endforeach
        </select>
    </div>
</div>

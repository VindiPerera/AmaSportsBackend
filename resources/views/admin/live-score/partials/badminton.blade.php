{{-- Badminton scoreboard — fields match the `racket_scores` block of
     MultiSportLiveScore in sport-mobile/src/services/firebaseService.ts --}}
<div class="space-y-5">
    <div class="grid grid-cols-3 gap-3 text-center text-xs font-black uppercase tracking-wider text-slate-500 pb-2 border-b border-slate-100">
        <div class="text-left font-bold text-slate-400">Game Set</div>
        <div class="text-slate-800">{{ $match->homeTeam->name }}</div>
        <div class="text-slate-800">{{ $match->awayTeam->name }}</div>
    </div>

    @foreach ([1, 2, 3] as $set)
        <div class="grid grid-cols-3 gap-3 items-center">
            <label class="text-xs font-bold text-slate-700">Set {{ $set }}</label>
            <input type="number" min="0" name="racket_scores[set{{ $set }}_home]" value="{{ $score['set' . $set . '_home'] ?? '' }}" 
                   class="rounded-xl bg-slate-50 border border-slate-200 px-3 py-2 text-center text-sm font-bold text-slate-800 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
            <input type="number" min="0" name="racket_scores[set{{ $set }}_away]" value="{{ $score['set' . $set . '_away'] ?? '' }}" 
                   class="rounded-xl bg-slate-50 border border-slate-200 px-3 py-2 text-center text-sm font-bold text-slate-800 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
        </div>
    @endforeach

    <div class="grid grid-cols-3 gap-3 items-center border-t border-slate-100 pt-5">
        <label class="text-xs font-black uppercase tracking-wider text-brand-red">Current Points</label>
        <input type="number" min="0" name="racket_scores[points_home]" value="{{ $score['points_home'] ?? '' }}" 
               class="rounded-xl bg-red-50/50 border border-red-200 px-3 py-2 text-center text-base font-black text-brand-red focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
        <input type="number" min="0" name="racket_scores[points_away]" value="{{ $score['points_away'] ?? '' }}" 
               class="rounded-xl bg-red-50/50 border border-red-200 px-3 py-2 text-center text-base font-black text-brand-red focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
    </div>

    <div class="pt-2">
        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Active Set In Progress</label>
        <select name="racket_scores[current_set]" 
                class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
            @foreach ([1, 2, 3] as $set)
                <option value="{{ $set }}" @selected(($score['current_set'] ?? 1) == $set)>Set {{ $set }}</option>
            @endforeach
        </select>
    </div>
</div>

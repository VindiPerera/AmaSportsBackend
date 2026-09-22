{{-- Volleyball scoreboard — fields match the `team_score` block of
     MultiSportLiveScore in sport-mobile/src/services/firebaseService.ts --}}
<div class="space-y-5">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">{{ $match->homeTeam->name }} — Total Points</label>
            <input type="number" min="0" name="team_score[home_total]" value="{{ $score['home_total'] ?? '' }}" 
                   class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-center text-lg font-black text-slate-800 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">{{ $match->awayTeam->name }} — Total Points</label>
            <input type="number" min="0" name="team_score[away_total]" value="{{ $score['away_total'] ?? '' }}" 
                   class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-center text-lg font-black text-slate-800 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
        </div>
    </div>

    <div class="border-t border-slate-100 pt-5">
        <p class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Set Scores Breakdown</p>
        <p class="text-xs font-semibold text-slate-500 mb-1.5">{{ $match->homeTeam->name }}</p>
        <div class="grid grid-cols-4 gap-2.5 mb-3">
            @for ($i = 0; $i < 4; $i++)
                <input type="number" min="0" name="team_score[home_sets_or_breakdown][]"
                       value="{{ $score['home_sets_or_breakdown'][$i] ?? '' }}" placeholder="Set {{ $i + 1 }}"
                       class="rounded-xl bg-slate-50 border border-slate-200 px-3 py-2 text-center text-sm font-bold text-slate-800 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
            @endfor
        </div>
        <p class="text-xs font-semibold text-slate-500 mb-1.5">{{ $match->awayTeam->name }}</p>
        <div class="grid grid-cols-4 gap-2.5">
            @for ($i = 0; $i < 4; $i++)
                <input type="number" min="0" name="team_score[away_sets_or_breakdown][]"
                       value="{{ $score['away_sets_or_breakdown'][$i] ?? '' }}" placeholder="Set {{ $i + 1 }}"
                       class="rounded-xl bg-slate-50 border border-slate-200 px-3 py-2 text-center text-sm font-bold text-slate-800 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
            @endfor
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-5">
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">{{ $match->homeTeam->name }} — Outs</label>
            <input type="number" min="0" name="team_score[outs_home]" value="{{ $score['outs_home'] ?? '' }}" 
                   class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2 text-center text-sm font-bold text-slate-800 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">{{ $match->awayTeam->name }} — Outs</label>
            <input type="number" min="0" name="team_score[outs_away]" value="{{ $score['outs_away'] ?? '' }}" 
                   class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2 text-center text-sm font-bold text-slate-800 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
        </div>
    </div>
</div>

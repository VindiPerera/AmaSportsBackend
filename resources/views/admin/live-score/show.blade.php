@extends('admin.layouts.app')

@section('title', 'Live Score Control Panel')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.matches.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-500 mb-2 inline-block">← Back to Matches</a>
        
        <div class="flex flex-wrap items-center gap-2 mb-2">
            <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-blue-50 text-blue-700 uppercase tracking-wide">
                {{ $match->sport->name }}
            </span>
            @if ($match->format)
                <span class="text-xs font-semibold text-slate-500">• {{ $match->format->name }}</span>
            @endif
            @if ($match->ageCategory)
                <span class="text-xs font-semibold text-slate-500">• {{ $match->ageCategory->name }}</span>
            @endif
            @if ($match->matchCategory)
                <span class="text-xs font-semibold text-slate-500">• {{ $match->matchCategory->name }}</span>
            @endif
        </div>

        <h1 class="text-3xl font-black text-slate-900 tracking-tight">
            {{ $match->homeTeam->name }} <span class="text-slate-400 font-normal">vs</span> {{ $match->awayTeam->name }}
        </h1>
        <p class="text-xs font-semibold text-slate-500 mt-1 flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            {{ $match->venue ?? 'Main Arena' }} • {{ $match->scheduled_at?->format('M j, Y • g:ia') ?? 'Scheduled' }}
        </p>
    </div>

    @unless ($firebaseConfigured)
        <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50/90 p-4 text-xs text-amber-900 flex items-start gap-3 shadow-sm">
            <span class="text-base">⚠️</span>
            <div class="flex-1">
                <span class="font-bold block mb-0.5">Firebase Realtime Sync Pending</span>
                Live updates will save to the database, but real-time push to mobile apps requires a Firebase service-account key in <code>config/firebase.php</code>.
            </div>
        </div>
    @endunless

    @if ($match->sport->slug === 'cricket')
        @include('admin.live-score.partials.cricket', ['score' => $currentScore])
    @else
        <div id="update-status" class="hidden mb-6 rounded-2xl border p-4 text-xs font-bold shadow-sm"></div>

        <form id="score-form" method="POST" class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-soft max-w-2xl">
            @csrf

            @include("admin.live-score.partials.{$match->sport->slug}", ['score' => $currentScore])

            <div class="mt-8 pt-6 border-t border-slate-100 flex items-center gap-3">
                @if ($match->status === 'upcoming')
                    <button type="submit" formaction="{{ route('admin.live-score.start', $match) }}"
                            class="rounded-xl bg-red-600 hover:bg-red-500 text-white px-6 py-2.5 text-xs font-black uppercase tracking-wider shadow-lg shadow-red-600/30 hover:scale-[1.01] transition-all">
                        Start Match Engine
                    </button>
                @endif

                @if ($match->status === 'live')
                    <button type="button" id="update-btn"
                            class="rounded-xl bg-blue-600 hover:bg-blue-500 text-white px-6 py-2.5 text-xs font-black uppercase tracking-wider shadow-lg shadow-blue-600/30 hover:scale-[1.01] transition-all">
                        Sync Scoreboard
                    </button>
                    <button type="submit" formaction="{{ route('admin.live-score.finish', $match) }}"
                            onclick="return confirm('Finish this match? This is final.')"
                            class="rounded-xl bg-slate-100 text-slate-800 px-6 py-2.5 text-xs font-bold hover:bg-slate-200 transition-colors">
                        Finish Match
                    </button>
                @endif

                @if ($match->status === 'finished')
                    <div class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold">
                        ✓ Match Concluded — Scoreboard is Locked (Read-Only)
                    </div>
                @endif
            </div>
        </form>

        <script>
            const form = document.getElementById('score-form');
            const updateBtn = document.getElementById('update-btn');
            const statusBox = document.getElementById('update-status');

            if (updateBtn) {
                updateBtn.addEventListener('click', async () => {
                    const body = new URLSearchParams(new FormData(form));
                    try {
                        const res = await fetch("{{ route('admin.live-score.update', $match) }}", {
                            method: 'POST',
                            headers: { 'Accept': 'application/json' },
                            body,
                        });
                        const data = await res.json();
                        statusBox.classList.remove('hidden', 'border-emerald-200', 'bg-emerald-50', 'text-emerald-800', 'border-amber-200', 'bg-amber-50', 'text-amber-800', 'border-red-200', 'bg-red-50', 'text-red-800');
                        if (!res.ok) {
                            statusBox.classList.add('border-red-200', 'bg-red-50', 'text-red-800');
                            statusBox.textContent = data.message || 'Update failed.';
                        } else if (!data.firebase_ok) {
                            statusBox.classList.add('border-amber-200', 'bg-amber-50', 'text-amber-800');
                            statusBox.textContent = 'Saved to database, but Firebase push failed — mobile app may not see instant push.';
                        } else {
                            statusBox.classList.add('border-emerald-200', 'bg-emerald-50', 'text-emerald-800');
                            statusBox.textContent = '✓ Score successfully updated and synced.';
                        }
                    } catch (e) {
                        statusBox.classList.remove('hidden');
                        statusBox.classList.add('border-red-200', 'bg-red-50', 'text-red-800');
                        statusBox.textContent = 'Network error — could not reach the server.';
                    }
                });
            }
        </script>
    @endif
@endsection

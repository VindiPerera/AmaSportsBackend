@extends('admin.layouts.app')

@section('title', 'Live Streaming — ' . $match->homeTeam->name . ' vs ' . $match->awayTeam->name)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.matches.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">&larr; Back to Matches</a>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-2">Live Streaming</h1>
        <p class="text-sm text-slate-500 mt-0.5">
            {{ $match->sport->name }} — {{ $match->homeTeam->name }} <span class="text-slate-400">vs</span> {{ $match->awayTeam->name }}
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft p-6 sm:p-8 max-w-2xl">
        @if ($match->status === 'finished')
            {{-- Closed: streaming can never be re-enabled once a match has finished. --}}
            <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4">
                <span class="text-lg">🔒</span>
                <div>
                    <p class="text-sm font-bold text-slate-800">Streaming closed</p>
                    <p class="text-xs text-slate-500 mt-1">
                        This match has finished. Live streaming access automatically disables when a match ends, whether or not it was ever unlocked.
                    </p>
                </div>
            </div>
        @elseif ($access && $access->isActive())
            {{-- Unlocked: URL is editable for free, no repayment needed. --}}
            <div class="flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 mb-6">
                <span class="text-lg">✓</span>
                <div>
                    <p class="text-sm font-bold text-green-800">Live streaming is enabled for this match</p>
                    <p class="text-xs text-green-700/80 mt-1">
                        Paid {{ $access->purchased_at?->format('M j, Y \a\t g:ia') ?? '—' }}
                        by {{ $access->paidByUser?->name ?? 'an admin' }}.
                        Access closes automatically when you mark this match Finished.
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.matches.stream.update-url', $match) }}" class="space-y-3">
                @csrf
                @method('PUT')
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide">YouTube Stream URL</label>
                <input type="url" name="youtube_stream_url" placeholder="https://youtube.com/watch?v=..."
                       value="{{ old('youtube_stream_url', $match->youtube_stream_url) }}"
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 text-sm font-bold shadow-lg shadow-blue-600/25 transition-all">
                    Save Stream URL
                </button>
            </form>
        @else
            {{-- Locked: needs the $5 unlock before a URL can be set at all. --}}
            <div class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 mb-6">
                <span class="text-lg">📺</span>
                <div>
                    <p class="text-sm font-bold text-amber-800">Live streaming is locked</p>
                    <p class="text-xs text-amber-700/80 mt-1">
                        Pay a one-time $5 to enable the YouTube embed for this match. Live Score itself always stays
                        free for players — this only unlocks the video stream. Access closes automatically once you
                        mark the match Finished, so a future match needs its own $5 unlock.
                    </p>
                </div>
            </div>

            @if ($access && $access->status === 'pending')
                <p class="text-xs text-slate-500 mb-3">
                    A payment was started but not completed. If you already finished checkout on PayPal, refresh this
                    page in a moment — otherwise, pay again below.
                </p>
            @endif

            <form method="POST" action="{{ route('admin.matches.stream.create-order', $match) }}">
                @csrf
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 text-sm font-bold shadow-lg shadow-blue-600/25 transition-all">
                    Pay $5 with PayPal to Enable Streaming
                </button>
            </form>
        @endif
    </div>
@endsection

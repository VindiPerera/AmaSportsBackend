@extends('admin.layouts.app')

@section('title', 'Live Streaming — ' . $match->homeTeam->name . ' vs ' . $match->awayTeam->name)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.matches.index') }}" class="text-xs font-bold text-slate-500 hover:text-brand-red transition-colors">&larr; Back to Matches</a>
        <h1 class="text-2xl font-black text-brand-charcoal tracking-tight mt-2">Live Video Streaming</h1>
        <p class="text-xs text-slate-500 mt-0.5">
            {{ $match->sport->name }} — {{ $match->homeTeam->name }} <span class="text-slate-400 font-normal">vs</span> {{ $match->awayTeam->name }}
        </p>
    </div>

    <x-card class="max-w-2xl p-6 sm:p-8">
        @if ($match->status === 'finished')
            <x-alert type="warning" title="Streaming Access Closed">
                This match has concluded. Video streaming automatically closes when a match finishes.
            </x-alert>
        @elseif ($access && $access->isActive())
            <x-alert type="success" title="Live Video Streaming is Active">
                Paid {{ $access->purchased_at?->format('M j, Y \a\t g:i A') ?? '—' }}
                by {{ $access->paidByUser?->name ?? 'an admin' }}. Access will close automatically when the match finishes.
            </x-alert>

            <form method="POST" action="{{ route('admin.matches.stream.update-url', $match) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <x-input
                    type="url"
                    name="youtube_stream_url"
                    label="YouTube Stream URL"
                    value="{{ $match->youtube_stream_url }}"
                    placeholder="https://youtube.com/watch?v=..."
                />

                <x-button type="submit" variant="primary" size="md">
                    Save Stream URL
                </x-button>
            </form>
        @else
            <x-alert type="warning" title="Live Streaming is Locked">
                Pay a one-time $5 activation to broadcast the live video stream for this fixture. Real-time live scoring is always free; this unlock enables the video embed.
            </x-alert>

            @if ($access && $access->status === 'pending')
                <p class="text-xs text-slate-500 mb-4 bg-slate-50 p-3 rounded-xl border border-slate-200">
                    A payment was started but not completed. If you already completed checkout on PayPal, refresh this page — otherwise, complete payment below.
                </p>
            @endif

            <form method="POST" action="{{ route('admin.matches.stream.create-order', $match) }}">
                @csrf
                <x-button type="submit" variant="primary" size="lg">
                    <span>Pay $5 with PayPal to Unlock Streaming</span>
                </x-button>
            </form>
        @endif
    </x-card>
@endsection

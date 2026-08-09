@extends('admin.layouts.app')

@section('title', 'Live Score Control')

@section('content')
    <div class="mb-6">
        <p class="text-sm text-gray-500">
            {{ $match->sport->name }} · {{ $match->format?->name }} · {{ $match->ageCategory?->name }} · {{ $match->matchCategory?->name }}
        </p>
        <h1 class="text-2xl font-semibold">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</h1>
        <p class="text-sm text-gray-500">{{ $match->venue }} · {{ $match->scheduled_at?->format('M j, Y g:ia') }}</p>
    </div>

    @unless ($firebaseConfigured)
        <div class="mb-4 rounded border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            ⚠ Firebase is not configured yet — live updates will not reach the mobile app until a service-account
            key is added (see <code>config/firebase.php</code>). Match data is still saved to the database.
        </div>
    @endunless

    @if ($match->sport->slug === 'cricket')
        {{-- The cricket scorer is fully self-contained: its own Start/Finish
             triggers, its own auto-syncing Update calls per ball, its own
             status banner. No shared form/buttons needed here. --}}
        @include('admin.live-score.partials.cricket', ['score' => $currentScore])
    @else
        <div id="update-status" class="hidden mb-4 rounded border px-4 py-3 text-sm"></div>

        <form id="score-form" method="POST" class="bg-white rounded-lg border border-gray-200 p-5 max-w-2xl">
            @csrf

            @include("admin.live-score.partials.{$match->sport->slug}", ['score' => $currentScore])

            <div class="mt-6 flex gap-3">
                @if ($match->status === 'upcoming')
                    <button type="submit" formaction="{{ route('admin.live-score.start', $match) }}"
                            class="rounded bg-red-600 text-white px-5 py-2.5 text-sm font-medium hover:bg-red-500">
                        Start
                    </button>
                @endif

                @if ($match->status === 'live')
                    <button type="button" id="update-btn"
                            class="rounded bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800">
                        Update
                    </button>
                    <button type="submit" formaction="{{ route('admin.live-score.finish', $match) }}"
                            onclick="return confirm('Finish this match? This is final.')"
                            class="rounded bg-gray-200 text-gray-800 px-5 py-2.5 text-sm font-medium hover:bg-gray-300">
                        Finish
                    </button>
                @endif

                @if ($match->status === 'finished')
                    <p class="text-sm text-gray-500 py-2.5">This match has finished — the scoreboard is read-only.</p>
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
                        statusBox.classList.remove('hidden', 'border-green-300', 'bg-green-50', 'text-green-800', 'border-amber-300', 'bg-amber-50', 'text-amber-800', 'border-red-300', 'bg-red-50', 'text-red-800');
                        if (!res.ok) {
                            statusBox.classList.add('border-red-300', 'bg-red-50', 'text-red-800');
                            statusBox.textContent = data.message || 'Update failed.';
                        } else if (!data.firebase_ok) {
                            statusBox.classList.add('border-amber-300', 'bg-amber-50', 'text-amber-800');
                            statusBox.textContent = 'Saved, but Firebase push failed — mobile app may not see this update.';
                        } else {
                            statusBox.classList.add('border-green-300', 'bg-green-50', 'text-green-800');
                            statusBox.textContent = 'Score updated.';
                        }
                    } catch (e) {
                        statusBox.classList.remove('hidden');
                        statusBox.classList.add('border-red-300', 'bg-red-50', 'text-red-800');
                        statusBox.textContent = 'Network error — could not reach the server.';
                    }
                });
            }
        </script>
    @endif
@endsection

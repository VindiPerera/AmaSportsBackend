@extends('admin.layouts.app')

@section('title', $match ? 'Edit Match Setup' : 'Create Match Setup')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.matches.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-500 mb-2 inline-block">← Back to Matches</a>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $match ? 'Edit Match Setup' : 'Create New Match' }}</h1>
        <p class="text-sm text-slate-500 mt-0.5">Configure sport parameters, scheduled venue, teams, and live stream link</p>
    </div>

    <form method="POST"
          action="{{ $match ? route('admin.matches.update', $match) : route('admin.matches.store') }}"
          enctype="multipart/form-data" class="space-y-8 max-w-4xl">
        @csrf
        @if ($match) @method('PUT') @endif

        {{-- Section 1: Match Details --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-soft space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">1</div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Match Meta & Classification</h2>
                    <p class="text-xs text-slate-500">Select sport, tournament format, date, venue and stream link</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Sport <span class="text-red-500">*</span></label>
                    <select name="sport_id" required class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        <option value="">Select Sport...</option>
                        @foreach ($sports as $sport)
                            <option value="{{ $sport->id }}" @selected(old('sport_id', $match?->sport_id) == $sport->id)>{{ $sport->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Format</label>
                    <select name="format_id" class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        <option value="">Select Format...</option>
                        @foreach ($formats as $format)
                            <option value="{{ $format->id }}" @selected(old('format_id', $match?->format_id) == $format->id)>{{ $format->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Age Category</label>
                    <select name="age_category_id" class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        <option value="">Select Age Group...</option>
                        @foreach ($ageCategories as $age)
                            <option value="{{ $age->id }}" @selected(old('age_category_id', $match?->age_category_id) == $age->id)>{{ $age->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Match Category</label>
                    <select name="match_category_id" class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        <option value="">Select Category...</option>
                        @foreach ($matchCategories as $category)
                            <option value="{{ $category->id }}" @selected(old('match_category_id', $match?->match_category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Scheduled Date & Time <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="scheduled_at"
                           value="{{ old('scheduled_at', $match?->scheduled_at?->format('Y-m-d\TH:i')) }}"
                           required class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Venue</label>
                    <input type="text" name="venue" placeholder="e.g. Royal College Ground - Colombo" value="{{ old('venue', $match?->venue) }}" 
                           class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Country</label>
                    <input type="text" name="country" placeholder="e.g. Sri Lanka" value="{{ old('country', $match?->country) }}" 
                           class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>

            </div>

            @if ($match)
                {{-- Live streaming is a separate paid unlock now (Phase 6 revision 2) — manage it from the Matches list's "Streaming" action, not here. --}}
                <p class="text-xs text-slate-500 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                    📺 Live streaming for this match is managed separately —
                    <a href="{{ route('admin.matches.stream.show', $match) }}" class="font-bold text-blue-600 hover:underline">open Live Streaming</a>
                    to pay the $5 unlock and set the YouTube URL.
                </p>
            @endif
        </div>

        {{-- Section 2: Organizer Contact --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-soft space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">2</div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Organizer Contact Info</h2>
                    <p class="text-xs text-slate-500">Contact details for team coordinators & tournament officials</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Mobile Phone</label>
                    <input type="text" name="contact_mobile" placeholder="+94 7X XXX XXXX" value="{{ old('contact_mobile', $match?->contact_mobile) }}" 
                           class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">WhatsApp Number</label>
                    <input type="text" name="contact_whatsapp" placeholder="+94 7X XXX XXXX" value="{{ old('contact_whatsapp', $match?->contact_whatsapp) }}" 
                           class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Contact Email</label>
                    <input type="email" name="contact_email" placeholder="organizer@tournament.org" value="{{ old('contact_email', $match?->contact_email) }}" 
                           class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
            </div>
        </div>

        {{-- Section 3: Teams Setup --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach (['home' => ['label' => 'Team A (Home Slot)', 'badge' => 'HOME', 'team' => $match?->homeTeam], 'away' => ['label' => 'Team B (Away Slot)', 'badge' => 'AWAY', 'team' => $match?->awayTeam]] as $side => $config)
                <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-soft space-y-4 team-picker relative" data-side="{{ $side }}">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h2 class="text-base font-extrabold text-slate-900">{{ $config['label'] }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-700">
                            {{ $config['badge'] }}
                        </span>
                    </div>

                    <input type="hidden" name="{{ $side }}_team_id" class="team-id-input" value="{{ old("{$side}_team_id", $config['team']?->id) }}">

                    <div class="relative">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Team / School / Club Name <span class="text-red-500">*</span></label>
                        <input type="text" name="{{ $side }}_team_name" autocomplete="off" placeholder="Start typing team name..."
                               value="{{ old("{$side}_team_name", $config['team']?->name) }}"
                               class="team-name-input w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        <ul class="team-suggestions hidden absolute z-30 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl text-sm max-h-48 overflow-y-auto divide-y divide-slate-100"></ul>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Country</label>
                        <input type="text" name="{{ $side }}_team_country" value="{{ old("{$side}_team_country", $config['team']?->country) }}" 
                               class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">School / Academy</label>
                        <input type="text" name="{{ $side }}_team_school_academy" value="{{ old("{$side}_team_school_academy", $config['team']?->school_academy) }}" 
                               class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Club</label>
                        <input type="text" name="{{ $side }}_team_club" value="{{ old("{$side}_team_club", $config['team']?->club) }}" 
                               class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 outline-none transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Logo Image</label>
                            <input type="file" name="{{ $side }}_logo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Team Photo</label>
                            <input type="file" name="{{ $side }}_photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Submit Button --}}
        <div class="pt-4">
            <button type="submit" class="w-full sm:w-auto rounded-xl bg-blue-600 hover:bg-blue-500 text-white px-8 py-3.5 text-sm font-extrabold shadow-lg shadow-blue-600/30 hover:scale-[1.01] transition-all">
                {{ $match ? 'Save Match Modifications' : 'Create Match & Continue to Roster Setup →' }}
            </button>
        </div>
    </form>

    <script>
        document.querySelectorAll('.team-picker').forEach((picker) => {
            const nameInput = picker.querySelector('.team-name-input');
            const idInput = picker.querySelector('.team-id-input');
            const list = picker.querySelector('.team-suggestions');
            const sportSelect = document.querySelector('select[name="sport_id"]');
            let debounce;

            nameInput.addEventListener('input', () => {
                idInput.value = '';
                clearTimeout(debounce);
                const q = nameInput.value.trim();
                if (q.length < 2) {
                    list.classList.add('hidden');
                    return;
                }
                debounce = setTimeout(async () => {
                    const params = new URLSearchParams({ q, sport_id: sportSelect.value || '' });
                    const res = await fetch(`{{ route('admin.teams.search') }}?${params}`);
                    const { data } = await res.json();
                    list.innerHTML = '';
                    if (!data.length) {
                        list.classList.add('hidden');
                        return;
                    }
                    data.forEach((team) => {
                        const li = document.createElement('li');
                        li.className = 'px-4 py-2.5 hover:bg-blue-50 text-slate-800 font-semibold cursor-pointer transition-colors';
                        li.textContent = team.name;
                        li.addEventListener('click', () => {
                            nameInput.value = team.name;
                            idInput.value = team.id;
                            picker.querySelector('[name$="_team_country"]').value = team.country || '';
                            picker.querySelector('[name$="_team_school_academy"]').value = team.school_academy || '';
                            picker.querySelector('[name$="_team_club"]').value = team.club || '';
                            list.classList.add('hidden');
                        });
                        list.appendChild(li);
                    });
                    list.classList.remove('hidden');
                }, 200);
            });

            document.addEventListener('click', (e) => {
                if (!picker.contains(e.target)) list.classList.add('hidden');
            });
        });
    </script>
@endsection

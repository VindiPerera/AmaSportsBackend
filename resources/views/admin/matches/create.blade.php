@extends('admin.layouts.app')

@section('title', $match ? 'Edit Match Setup' : 'Create Match Setup')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.matches.index') }}" class="text-xs font-bold text-slate-500 hover:text-brand-red mb-2 inline-block transition-colors">← Back to Matches</a>
        <h1 class="text-2xl font-black text-brand-charcoal tracking-tight">{{ $match ? 'Edit Match Setup' : 'Create New Match' }}</h1>
        <p class="text-xs text-slate-500 mt-1">Configure sport parameters, scheduled venue, teams, and live stream link</p>
    </div>

    <form method="POST"
          action="{{ $match ? route('admin.matches.update', $match) : route('admin.matches.store') }}"
          enctype="multipart/form-data" class="space-y-8 max-w-4xl">
        @csrf
        @if ($match) @method('PUT') @endif

        {{-- Section 1: Match Details --}}
        <x-card class="p-6 space-y-6">
            <x-slot:header>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-red-50 text-brand-red flex items-center justify-center font-black text-sm border border-red-100">1</div>
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Match Meta &amp; Classification</h2>
                        <p class="text-xs text-slate-500">Select sport, tournament format, date, venue, and stream link</p>
                    </div>
                </div>
            </x-slot:header>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <x-select name="sport_id" label="Sport" required>
                    <option value="">Select Sport...</option>
                    @foreach ($sports as $sport)
                        <option value="{{ $sport->id }}" @selected(old('sport_id', $match?->sport_id) == $sport->id)>{{ $sport->name }}</option>
                    @endforeach
                </x-select>

                <x-select name="format_id" label="Format">
                    <option value="">Select Format...</option>
                    @foreach ($formats as $format)
                        <option value="{{ $format->id }}" @selected(old('format_id', $match?->format_id) == $format->id)>{{ $format->name }}</option>
                    @endforeach
                </x-select>

                <x-select name="age_category_id" label="Age Category">
                    <option value="">Select Age Group...</option>
                    @foreach ($ageCategories as $age)
                        <option value="{{ $age->id }}" @selected(old('age_category_id', $match?->age_category_id) == $age->id)>{{ $age->name }}</option>
                    @endforeach
                </x-select>

                <x-select name="match_category_id" label="Match Category">
                    <option value="">Select Category...</option>
                    @foreach ($matchCategories as $category)
                        <option value="{{ $category->id }}" @selected(old('match_category_id', $match?->match_category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </x-select>

                <x-input
                    type="datetime-local"
                    name="scheduled_at"
                    label="Scheduled Date & Time"
                    value="{{ $match?->scheduled_at?->format('Y-m-d\TH:i') }}"
                    required
                />

                <x-input
                    name="venue"
                    label="Venue"
                    value="{{ $match?->venue }}"
                    placeholder="e.g. Royal College Ground - Colombo"
                />

                <x-input
                    name="country"
                    label="Country"
                    value="{{ $match?->country }}"
                    placeholder="e.g. Sri Lanka"
                />
            </div>

            @if ($match)
                <div class="text-xs text-slate-600 bg-amber-50/70 border border-amber-200/80 rounded-2xl p-4 flex items-center gap-3">
                    <span class="text-lg">📺</span>
                    <div>
                        <span>Live streaming for this match is managed separately — </span>
                        <a href="{{ route('admin.matches.stream.show', $match) }}" class="font-bold text-brand-red hover:underline">open Live Streaming</a>
                        <span> to configure YouTube URL and access options.</span>
                    </div>
                </div>
            @endif
        </x-card>

        {{-- Section 2: Organizer Contact --}}
        <x-card class="p-6 space-y-6">
            <x-slot:header>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-800 flex items-center justify-center font-black text-sm border border-amber-200">2</div>
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Organizer Contact Info</h2>
                        <p class="text-xs text-slate-500">Contact details for team coordinators &amp; tournament officials</p>
                    </div>
                </div>
            </x-slot:header>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <x-input
                    name="contact_mobile"
                    label="Mobile Phone"
                    value="{{ $match?->contact_mobile }}"
                    placeholder="+94 7X XXX XXXX"
                />

                <x-input
                    name="contact_whatsapp"
                    label="WhatsApp Number"
                    value="{{ $match?->contact_whatsapp }}"
                    placeholder="+94 7X XXX XXXX"
                />

                <x-input
                    type="email"
                    name="contact_email"
                    label="Contact Email"
                    value="{{ $match?->contact_email }}"
                    placeholder="organizer@tournament.org"
                />
            </div>
        </x-card>

        {{-- Section 3: Teams Setup --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach (['home' => ['label' => 'Team A (Home Slot)', 'badge' => 'HOME', 'team' => $match?->homeTeam, 'color' => 'red'], 'away' => ['label' => 'Team B (Away Slot)', 'badge' => 'AWAY', 'team' => $match?->awayTeam, 'color' => 'gold']] as $side => $config)
                <x-card class="p-6 space-y-4 team-picker relative" data-side="{{ $side }}">
                    <x-slot:header>
                        <div class="flex items-center justify-between w-full">
                            <h2 class="text-sm font-extrabold text-slate-900">{{ $config['label'] }}</h2>
                            <x-badge :variant="$config['color'] === 'red' ? 'red' : 'gold'" size="sm">
                                {{ $config['badge'] }}
                            </x-badge>
                        </div>
                    </x-slot:header>

                    <input type="hidden" name="{{ $side }}_team_id" class="team-id-input" value="{{ old("{$side}_team_id", $config['team']?->id) }}">

                    <div class="relative">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Team Name <span class="text-brand-red">*</span></label>
                        <input type="text" name="{{ $side }}_team_name" autocomplete="off" placeholder="Start typing team name..."
                               value="{{ old("{$side}_team_name", $config['team']?->name) }}"
                               class="team-name-input w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
                        <ul class="team-suggestions hidden absolute z-30 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl text-sm max-h-48 overflow-y-auto divide-y divide-slate-100"></ul>
                    </div>

                    <x-input
                        name="{{ $side }}_team_country"
                        label="Country"
                        value="{{ $config['team']?->country }}"
                    />

                    <x-input
                        name="{{ $side }}_team_school_academy"
                        label="School / Academy"
                        value="{{ $config['team']?->school_academy }}"
                    />

                    <x-input
                        name="{{ $side }}_team_club"
                        label="Club"
                        value="{{ $config['team']?->club }}"
                    />

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Logo Image</label>
                            <input type="file" name="{{ $side }}_logo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Team Photo</label>
                            <input type="file" name="{{ $side }}_photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>

        {{-- Submit Button --}}
        <div class="pt-4 flex items-center gap-4">
            <x-button type="submit" variant="primary" size="lg">
                {{ $match ? 'Save Match Modifications' : 'Create Match & Continue to Roster Setup →' }}
            </x-button>
            <x-button href="{{ route('admin.matches.index') }}" variant="secondary" size="lg">
                Cancel
            </x-button>
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
                    const params = new URLSearchParams({ q, sport_id: sportSelect ? sportSelect.value || '' : '' });
                    const res = await fetch(`{{ route('admin.teams.search') }}?${params}`);
                    const { data } = await res.json();
                    list.innerHTML = '';
                    if (!data || !data.length) {
                        list.classList.add('hidden');
                        return;
                    }
                    data.forEach((team) => {
                        const li = document.createElement('li');
                        li.className = 'px-4 py-2.5 hover:bg-red-50 text-slate-800 font-semibold cursor-pointer transition-colors';
                        li.textContent = team.name;
                        li.addEventListener('click', () => {
                            nameInput.value = team.name;
                            idInput.value = team.id;
                            const countryInput = picker.querySelector('[name$="_team_country"]');
                            const schoolInput = picker.querySelector('[name$="_team_school_academy"]');
                            const clubInput = picker.querySelector('[name$="_team_club"]');
                            if (countryInput) countryInput.value = team.country || '';
                            if (schoolInput) schoolInput.value = team.school_academy || '';
                            if (clubInput) clubInput.value = team.club || '';
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

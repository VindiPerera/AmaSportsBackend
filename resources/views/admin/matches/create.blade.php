@extends('admin.layouts.app')

@section('title', $match ? 'Edit Match' : 'New Match')

@section('content')
    <h1 class="text-2xl font-semibold mb-6">{{ $match ? 'Edit Match' : 'Match Setup' }}</h1>

    <form method="POST"
          action="{{ $match ? route('admin.matches.update', $match) : route('admin.matches.store') }}"
          enctype="multipart/form-data" class="space-y-8 max-w-3xl">
        @csrf
        @if ($match) @method('PUT') @endif

        <div class="bg-white rounded-lg border border-gray-200 p-5 space-y-4">
            <h2 class="font-medium">Match details</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sport</label>
                    <select name="sport_id" required class="mt-1 w-full rounded border-gray-300">
                        <option value="">Select…</option>
                        @foreach ($sports as $sport)
                            <option value="{{ $sport->id }}" @selected(old('sport_id', $match?->sport_id) == $sport->id)>{{ $sport->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Format</label>
                    <select name="format_id" class="mt-1 w-full rounded border-gray-300">
                        <option value="">Select…</option>
                        @foreach ($formats as $format)
                            <option value="{{ $format->id }}" @selected(old('format_id', $match?->format_id) == $format->id)>{{ $format->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Age category</label>
                    <select name="age_category_id" class="mt-1 w-full rounded border-gray-300">
                        <option value="">Select…</option>
                        @foreach ($ageCategories as $age)
                            <option value="{{ $age->id }}" @selected(old('age_category_id', $match?->age_category_id) == $age->id)>{{ $age->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="match_category_id" class="mt-1 w-full rounded border-gray-300">
                        <option value="">Select…</option>
                        @foreach ($matchCategories as $category)
                            <option value="{{ $category->id }}" @selected(old('match_category_id', $match?->match_category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Date &amp; time</label>
                    <input type="datetime-local" name="scheduled_at"
                           value="{{ old('scheduled_at', $match?->scheduled_at?->format('Y-m-d\TH:i')) }}"
                           required class="mt-1 w-full rounded border-gray-300">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Venue</label>
                    <input type="text" name="venue" value="{{ old('venue', $match?->venue) }}" class="mt-1 w-full rounded border-gray-300">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Country</label>
                    <input type="text" name="country" value="{{ old('country', $match?->country) }}" class="mt-1 w-full rounded border-gray-300">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">YouTube stream URL</label>
                    <input type="url" name="youtube_stream_url" value="{{ old('youtube_stream_url', $match?->youtube_stream_url) }}" class="mt-1 w-full rounded border-gray-300">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-5 space-y-4">
            <h2 class="font-medium">Organizer contact</h2>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mobile No</label>
                    <input type="text" name="contact_mobile" value="{{ old('contact_mobile', $match?->contact_mobile) }}" class="mt-1 w-full rounded border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">WhatsApp No</label>
                    <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp', $match?->contact_whatsapp) }}" class="mt-1 w-full rounded border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $match?->contact_email) }}" class="mt-1 w-full rounded border-gray-300">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            @foreach (['home' => ['label' => 'Team A (Home)', 'team' => $match?->homeTeam], 'away' => ['label' => 'Team B (Away)', 'team' => $match?->awayTeam]] as $side => $config)
                <div class="bg-white rounded-lg border border-gray-200 p-5 space-y-3 team-picker" data-side="{{ $side }}">
                    <h2 class="font-medium">{{ $config['label'] }}</h2>

                    <input type="hidden" name="{{ $side }}_team_id" class="team-id-input" value="{{ old("{$side}_team_id", $config['team']?->id) }}">

                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700">Team / Country / School name</label>
                        <input type="text" name="{{ $side }}_team_name" autocomplete="off"
                               value="{{ old("{$side}_team_name", $config['team']?->name) }}"
                               class="team-name-input mt-1 w-full rounded border-gray-300">
                        <ul class="team-suggestions hidden absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded shadow text-sm max-h-48 overflow-y-auto"></ul>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Country</label>
                        <input type="text" name="{{ $side }}_team_country" value="{{ old("{$side}_team_country", $config['team']?->country) }}" class="mt-1 w-full rounded border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">School / Academy</label>
                        <input type="text" name="{{ $side }}_team_school_academy" value="{{ old("{$side}_team_school_academy", $config['team']?->school_academy) }}" class="mt-1 w-full rounded border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Club</label>
                        <input type="text" name="{{ $side }}_team_club" value="{{ old("{$side}_team_club", $config['team']?->club) }}" class="mt-1 w-full rounded border-gray-300">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Logo</label>
                            <input type="file" name="{{ $side }}_logo" accept="image/*" class="mt-1 w-full text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Photo</label>
                            <input type="file" name="{{ $side }}_photo" accept="image/*" class="mt-1 w-full text-sm">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="submit" class="rounded bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800">
            {{ $match ? 'Save changes' : 'Create match & continue to roster' }}
        </button>
    </form>

    <script>
        // Minimal typeahead against /admin/teams/search — lets an admin
        // reuse an existing team instead of retyping it every match.
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
                        li.className = 'px-3 py-2 hover:bg-gray-100 cursor-pointer';
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

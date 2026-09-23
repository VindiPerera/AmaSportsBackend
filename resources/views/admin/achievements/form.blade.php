@extends('admin.layouts.app')

@section('title', $achievement ? 'Edit Achievement' : 'Create Achievement')

@section('content')
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>

    <div class="mb-6">
        <a href="{{ route('admin.achievements.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-brand-red mb-3 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Achievements
        </a>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $achievement ? 'Edit Achievement' : 'Create Achievement' }}</h1>
        <p class="text-xs font-semibold text-slate-500 mt-0.5">{{ $achievement ? 'Update this badge — players who already unlocked or posted it are unaffected.' : 'Define a stat threshold that unlocks a postable badge.' }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start max-w-4xl">
        <form method="POST"
              action="{{ $achievement ? route('admin.achievements.update', $achievement) : route('admin.achievements.store') }}"
              class="lg:col-span-2">
            @csrf
            @if ($achievement) @method('PUT') @endif

            <x-card class="p-6 space-y-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Title <span class="text-brand-red">*</span></label>
                    <input type="text" name="title" id="field-title" required maxlength="255" placeholder="Century Club"
                           value="{{ old('title', $achievement?->title) }}"
                           class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
                    @error('title') <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Description</label>
                    <textarea name="description" rows="2" maxlength="1000" placeholder="Score 100 or more runs in your career."
                              class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">{{ old('description', $achievement?->description) }}</textarea>
                    @error('description') <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Metric <span class="text-brand-red">*</span></label>
                        <select name="metric_key" required
                                class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
                            <option value="">Select metric...</option>
                            @foreach ($metrics as $key => $label)
                                <option value="{{ $key }}" @selected(old('metric_key', $achievement?->metric_key) === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('metric_key') <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Threshold <span class="text-brand-red">*</span></label>
                        <input type="number" name="threshold" id="field-threshold" min="1" required placeholder="100"
                               value="{{ old('threshold', $achievement?->threshold) }}"
                               class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
                        @error('threshold') <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Icon <span class="text-brand-red">*</span></label>
                        <select name="icon" id="field-icon" required
                                class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
                            @foreach ($icons as $name => $label)
                                <option value="{{ $name }}" @selected(old('icon', $achievement?->icon ?? 'trophy') === $name)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('icon') <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Accent Color <span class="text-brand-red">*</span></label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="color" id="field-color"
                                   value="{{ old('color', $achievement?->color ?? '#FABD15') }}"
                                   class="h-10 w-14 rounded-xl border border-slate-200 bg-slate-50 cursor-pointer p-1">
                            <input type="text" id="field-color-hex" readonly
                                   value="{{ old('color', $achievement?->color ?? '#FABD15') }}"
                                   class="flex-1 rounded-xl bg-slate-100 border border-slate-200 px-3 py-2 text-xs font-mono text-slate-600 font-bold uppercase">
                        </div>
                        @error('color') <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                               @checked(old('is_active', $achievement ? $achievement->is_active : true))
                               class="rounded border-slate-300 text-brand-red focus:ring-brand-red/30">
                        <span class="text-xs font-bold text-slate-700">Active (can be unlocked by players)</span>
                    </label>
                </div>
            </x-card>

            <div class="pt-6">
                <x-button type="submit" variant="primary" size="lg">
                    {{ $achievement ? 'Save Achievement' : 'Create Achievement' }}
                </x-button>
            </div>
        </form>

        {{-- Live badge preview --}}
        <div class="lg:sticky lg:top-20">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Live App Preview</p>
            <x-card class="p-6 text-center">
                <div id="preview-badge" class="w-20 h-20 mx-auto rounded-full flex items-center justify-center shadow-lg transition-all"
                     style="background: linear-gradient(135deg, #FABD15, #FABD15cc);">
                    <ion-icon id="preview-icon" name="trophy" style="color: white; font-size: 36px;"></ion-icon>
                </div>
                <p id="preview-title" class="text-slate-900 font-extrabold mt-4 text-base">Achievement Title</p>
                <p id="preview-threshold" class="text-slate-500 text-xs font-semibold mt-1">Threshold: —</p>
            </x-card>
        </div>
    </div>

    <script>
        const titleInput = document.getElementById('field-title');
        const thresholdInput = document.getElementById('field-threshold');
        const iconSelect = document.getElementById('field-icon');
        const colorInput = document.getElementById('field-color');
        const colorHex = document.getElementById('field-color-hex');
        const previewBadge = document.getElementById('preview-badge');
        const previewIcon = document.getElementById('preview-icon');
        const previewTitle = document.getElementById('preview-title');
        const previewThreshold = document.getElementById('preview-threshold');

        function refreshPreview() {
            previewTitle.textContent = titleInput.value.trim() || 'Achievement Title';
            previewThreshold.textContent = thresholdInput.value ? `Threshold: ${Number(thresholdInput.value).toLocaleString()}` : 'Threshold: —';
            previewIcon.setAttribute('name', iconSelect.value || 'trophy');
            const color = colorInput.value || '#F59E0B';
            previewBadge.style.background = `linear-gradient(135deg, ${color}, ${color}cc)`;
            colorHex.value = color;
        }

        [titleInput, thresholdInput, iconSelect, colorInput].forEach((el) => el.addEventListener('input', refreshPreview));
        refreshPreview();
    </script>
@endsection

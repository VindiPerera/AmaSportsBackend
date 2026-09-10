@extends('admin.layouts.app')

@section('title', $achievement ? 'Edit Achievement' : 'Create Achievement')

@section('content')
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>

    <div class="mb-8">
        <a href="{{ route('admin.achievements.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-500 mb-2 inline-block">← Back to Achievements</a>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $achievement ? 'Edit Achievement' : 'Create Achievement' }}</h1>
        <p class="text-sm text-slate-500 mt-0.5">{{ $achievement ? 'Update this badge — players who already unlocked or posted it are unaffected.' : 'Define a stat threshold that unlocks a postable badge.' }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start max-w-4xl">
        <form method="POST"
              action="{{ $achievement ? route('admin.achievements.update', $achievement) : route('admin.achievements.store') }}"
              class="lg:col-span-2">
            @csrf
            @if ($achievement) @method('PUT') @endif

            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-soft space-y-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="field-title" required maxlength="255" placeholder="Century Club"
                           value="{{ old('title', $achievement?->title) }}"
                           class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                    @error('title') <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Description</label>
                    <textarea name="description" rows="2" maxlength="1000" placeholder="Score 100 or more runs in your career."
                              class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">{{ old('description', $achievement?->description) }}</textarea>
                    @error('description') <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Metric <span class="text-red-500">*</span></label>
                        <select name="metric_key" required
                                class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                            <option value="">Select metric...</option>
                            @foreach ($metrics as $key => $label)
                                <option value="{{ $key }}" @selected(old('metric_key', $achievement?->metric_key) === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('metric_key') <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Threshold <span class="text-red-500">*</span></label>
                        <input type="number" name="threshold" id="field-threshold" min="1" required placeholder="100"
                               value="{{ old('threshold', $achievement?->threshold) }}"
                               class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        @error('threshold') <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Icon <span class="text-red-500">*</span></label>
                        <select name="icon" id="field-icon"
                                class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                            @foreach ($icons as $icon)
                                <option value="{{ $icon }}" @selected(old('icon', $achievement?->icon ?? 'trophy') === $icon)>{{ ucwords(str_replace('-', ' ', $icon)) }}</option>
                            @endforeach
                        </select>
                        @error('icon') <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Badge Color <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="color" id="field-color" value="{{ old('color', $achievement?->color ?? '#F59E0B') }}"
                                   class="w-12 h-[42px] rounded-xl border border-slate-200 cursor-pointer">
                            <input type="text" id="field-color-hex" readonly value="{{ old('color', $achievement?->color ?? '#F59E0B') }}"
                                   class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-500">
                        </div>
                        @error('color') <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Sort Order</label>
                        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $achievement?->sort_order ?? 0) }}"
                               class="w-28 rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                    </div>
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $achievement?->is_active ?? true))
                               class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500/30">
                        <span class="text-sm font-bold text-slate-700">Active (players can unlock it)</span>
                    </label>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full sm:w-auto rounded-xl bg-blue-600 hover:bg-blue-500 text-white px-8 py-3.5 text-sm font-extrabold shadow-lg shadow-blue-600/30 hover:scale-[1.01] transition-all">
                    {{ $achievement ? 'Save Achievement' : 'Create Achievement' }}
                </button>
            </div>
        </form>

        {{-- Live badge preview — same icon/color the mobile app will render. --}}
        <div class="lg:sticky lg:top-20">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Preview</p>
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-6 shadow-soft text-center">
                <div id="preview-badge" class="w-20 h-20 mx-auto rounded-full flex items-center justify-center shadow-lg transition-all"
                     style="background: linear-gradient(135deg, #F59E0B, #F59E0Bcc);">
                    <ion-icon id="preview-icon" name="trophy" style="color: white; font-size: 36px;"></ion-icon>
                </div>
                <p id="preview-title" class="text-white font-extrabold mt-4 text-base">Achievement Title</p>
                <p id="preview-threshold" class="text-slate-400 text-xs font-semibold mt-1">Threshold: —</p>
            </div>
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

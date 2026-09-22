@props([
    'group' => null,
    'tabs' => [], // ['id' => 'tab-1', 'label' => 'Overview', 'active' => true, 'count' => null]
])

<div class="border-b border-slate-200">
    <nav class="-mb-px flex space-x-6 overflow-x-auto" aria-label="Tabs" @if($group) data-tab-group="{{ $group }}" @endif>
        @if(!empty($tabs))
            @foreach($tabs as $tab)
                @php
                    $isActive = $tab['active'] ?? false;
                @endphp
                <button
                    type="button"
                    @if($group) data-tab-target="{{ $tab['id'] }}" @endif
                    class="shrink-0 border-b-2 py-3 px-1 text-sm font-bold tracking-tight transition-all flex items-center gap-2 cursor-pointer {{ $isActive ? 'border-brand-red text-brand-red' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }}"
                    aria-selected="{{ $isActive ? 'true' : 'false' }}"
                >
                    <span>{{ $tab['label'] }}</span>
                    @if(isset($tab['count']) && $tab['count'] !== null)
                        <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $isActive ? 'bg-red-50 text-brand-red' : 'bg-slate-100 text-slate-600' }}">
                            {{ $tab['count'] }}
                        </span>
                    @endif
                </button>
            @endforeach
        @else
            {{ $slot }}
        @endif
    </nav>
</div>

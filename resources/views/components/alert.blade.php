@props([
    'type' => 'info', // success, error, warning, info
    'title' => null,
    'dismissible' => true,
])

@php
    $typeConfig = match($type) {
        'success' => [
            'bg' => 'bg-emerald-50/90 border-emerald-200 text-emerald-900',
            'icon' => '<svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'close' => 'text-emerald-700 hover:bg-emerald-100',
        ],
        'error', 'danger' => [
            'bg' => 'bg-rose-50/90 border-rose-200 text-rose-950',
            'icon' => '<svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
            'close' => 'text-rose-700 hover:bg-rose-100',
        ],
        'warning' => [
            'bg' => 'bg-amber-50/90 border-amber-200 text-amber-950',
            'icon' => '<svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
            'close' => 'text-amber-700 hover:bg-amber-100',
        ],
        default => [
            'bg' => 'bg-sky-50/90 border-sky-200 text-sky-950',
            'icon' => '<svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'close' => 'text-sky-700 hover:bg-sky-100',
        ],
    };
@endphp

<div data-alert class="rounded-2xl border p-4 mb-5 transition-all duration-200 flex items-start gap-3 shadow-2xs {{ $typeConfig['bg'] }}" role="alert">
    <div class="shrink-0 mt-0.5">
        {!! $typeConfig['icon'] !!}
    </div>

    <div class="flex-1 text-sm font-medium">
        @if($title)
            <h4 class="font-bold text-sm mb-0.5">{{ $title }}</h4>
        @endif
        <div class="leading-relaxed">
            {{ $slot }}
        </div>
    </div>

    @if($dismissible)
        <button type="button" data-alert-dismiss class="shrink-0 rounded-lg p-1 transition-colors {{ $typeConfig['close'] }}" aria-label="Dismiss alert">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    @endif
</div>

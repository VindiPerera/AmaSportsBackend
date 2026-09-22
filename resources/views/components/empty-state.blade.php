@props([
    'title' => 'No records found',
    'message' => 'There are no items to display at this time.',
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'text-center py-12 px-6 rounded-2xl border-2 border-dashed border-slate-200 bg-white/50']) }}>
    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-400 border border-slate-100">
        @if($icon)
            {!! $icon !!}
        @else
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
        @endif
    </div>

    <h3 class="mt-4 text-base font-bold text-slate-900 tracking-tight">{{ $title }}</h3>
    <p class="mt-1.5 text-sm text-slate-500 max-w-sm mx-auto leading-relaxed">{{ $message }}</p>

    @if($slot->isNotEmpty())
        <div class="mt-6 flex justify-center gap-3">
            {{ $slot }}
        </div>
    @endif
</div>

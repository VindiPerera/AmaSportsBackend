@props([
    'hover' => false,
    'padding' => 'p-5 sm:p-6',
    'header' => null,
    'footer' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl border border-slate-200/80 shadow-xs ' . ($hover ? 'transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:border-slate-300' : '')]) }}>
    @if($header)
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            {{ $header }}
        </div>
    @endif

    <div class="{{ $padding }}">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="px-5 py-3.5 bg-slate-50/70 border-t border-slate-100 rounded-b-2xl">
            {{ $footer }}
        </div>
    @endif
</div>

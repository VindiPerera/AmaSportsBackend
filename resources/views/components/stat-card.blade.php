@props([
    'label',
    'value',
    'subtext' => null,
    'icon' => null,
    'accent' => null, // 'red', 'gold', or null
    'trend' => null,  // 'up', 'down', or string
])

@php
    $accentBorder = match($accent) {
        'red' => 'border-l-4 border-l-brand-red',
        'gold' => 'border-l-4 border-l-brand-gold',
        default => '',
    };
@endphp

<div {{ $attributes->merge(['class' => "bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between {$accentBorder}"]) }}>
    <div class="flex items-center justify-between gap-2">
        <span class="text-xs font-bold tracking-wider text-slate-500 uppercase">{{ $label }}</span>
        @if($icon)
            <span class="p-2 rounded-xl bg-slate-50 text-slate-700 border border-slate-100 flex items-center justify-center">
                {!! $icon !!}
            </span>
        @endif
    </div>

    <div class="mt-2 flex items-baseline gap-2">
        <span class="text-2xl sm:text-3xl font-black text-brand-charcoal tracking-tight">{{ $value }}</span>
        @if($subtext)
            <span class="text-xs font-semibold text-slate-500">{{ $subtext }}</span>
        @endif
    </div>

    @if($trend)
        <div class="mt-2 text-xs font-semibold text-emerald-600 flex items-center gap-1">
            <span>{{ $trend }}</span>
        </div>
    @endif
</div>

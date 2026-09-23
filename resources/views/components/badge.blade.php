@props([
    'variant' => 'neutral', // gold, red, neutral, live, success, warning, info
    'size' => 'md',        // sm, md
    'dot' => false,
])

@php
    $baseClasses = 'inline-flex items-center font-bold tracking-tight rounded-full transition-colors';

    $sizeClasses = match($size) {
        'sm' => 'text-[10px] px-2 py-0.5 gap-1',
        default => 'text-xs px-2.5 py-1 gap-1.5',
    };

    $variantClasses = match($variant) {
        'gold' => 'bg-amber-50 text-amber-900 border border-amber-300/80',
        'red' => 'bg-red-50 text-brand-red border border-red-200',
        'live' => 'bg-red-50 text-brand-red border border-red-200 font-extrabold uppercase tracking-wider',
        'success' => 'bg-emerald-50 text-emerald-800 border border-emerald-200',
        'warning' => 'bg-amber-50 text-amber-800 border border-amber-200',
        'info' => 'bg-sky-50 text-sky-800 border border-sky-200',
        'dark' => 'bg-brand-charcoal text-white',
        default => 'bg-slate-100 text-slate-700 border border-slate-200/80',
    };

    $dotClasses = match($variant) {
        'live', 'red' => 'bg-brand-red',
        'gold', 'warning' => 'bg-amber-500',
        'success' => 'bg-emerald-500',
        'info' => 'bg-sky-500',
        default => 'bg-slate-400',
    };
@endphp

<span {{ $attributes->merge(['class' => "{$baseClasses} {$sizeClasses} {$variantClasses}"]) }}>
    @if($dot || $variant === 'live')
        <span class="relative flex h-2 w-2">
            @if($variant === 'live')
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $dotClasses }} opacity-75"></span>
            @endif
            <span class="relative inline-flex rounded-full h-2 w-2 {{ $dotClasses }}"></span>
        </span>
    @endif
    {{ $slot }}
</span>

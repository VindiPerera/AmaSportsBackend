@props([
    'variant' => 'primary', // primary (red), gold, dark (charcoal), secondary (outline), ghost, danger
    'size' => 'md',       // sm, md, lg
    'href' => null,
    'type' => 'button',
    'icon' => null,
    'iconRight' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-bold tracking-tight rounded-xl transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none cursor-pointer';

    $sizeClasses = match($size) {
        'sm' => 'text-xs px-3 py-1.5 gap-1.5',
        'lg' => 'text-base px-6 py-3 gap-2.5 shadow-sm',
        default => 'text-sm px-4 py-2 gap-2',
    };

    $variantClasses = match($variant) {
        'primary' => 'bg-brand-red text-white hover:bg-brand-red-hover active:translate-y-0 shadow-sm shadow-brand-red/25 hover:shadow-md hover:shadow-brand-red/35 focus:ring-brand-red',
        'gold' => 'bg-brand-gold text-brand-charcoal hover:bg-brand-gold-hover shadow-sm shadow-brand-gold/25 hover:shadow-md hover:shadow-brand-gold/35 focus:ring-brand-gold',
        'dark' => 'bg-brand-charcoal text-white hover:bg-black focus:ring-brand-charcoal',
        'secondary', 'outline' => 'border border-slate-300 bg-white text-slate-800 hover:bg-slate-50 hover:border-slate-400 focus:ring-slate-400',
        'ghost' => 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 focus:ring-slate-300',
        'danger' => 'bg-status-error text-white hover:bg-red-700 shadow-sm shadow-red-500/20 focus:ring-red-600',
        'quiet' => 'text-brand-red bg-brand-red-light/80 hover:bg-brand-red-light font-semibold focus:ring-brand-red',
        default => 'bg-brand-red text-white hover:bg-brand-red-hover focus:ring-brand-red',
    };

    $classes = "{$baseClasses} {$sizeClasses} {$variantClasses}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon) <span>{!! $icon !!}</span> @endif
        {{ $slot }}
        @if($iconRight) <span>{!! $iconRight !!}</span> @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon) <span>{!! $icon !!}</span> @endif
        {{ $slot }}
        @if($iconRight) <span>{!! $iconRight !!}</span> @endif
    </button>
@endif

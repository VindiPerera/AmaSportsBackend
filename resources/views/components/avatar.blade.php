@props([
    'src' => null,
    'name' => '',
    'size' => 'md', // sm (32px), md (40px), lg (48px), xl (64px), 2xl (96px)
    'ring' => false,
])

@php
    $sizeClasses = match($size) {
        'sm' => 'w-8 h-8 text-xs',
        'lg' => 'w-12 h-12 text-base',
        'xl' => 'w-16 h-16 text-lg font-bold',
        '2xl' => 'w-24 h-24 text-2xl font-extrabold',
        default => 'w-10 h-10 text-sm font-semibold',
    };

    $initials = '';
    if ($name) {
        $parts = preg_split('/\s+/', trim($name));
        $initials = count($parts) >= 2 
            ? mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1))
            : mb_strtoupper(mb_substr($name, 0, 2));
    }

    $ringClasses = $ring ? 'ring-2 ring-brand-red/30 ring-offset-2' : '';
@endphp

<div {{ $attributes->merge(['class' => "relative inline-flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-slate-100 border border-slate-200 text-slate-700 select-none {$sizeClasses} {$ringClasses}"]) }}>
    @if($src)
        <img src="{{ $src }}" alt="{{ $name }}" class="w-full h-full object-cover" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
        <span class="hidden w-full h-full items-center justify-center bg-slate-100 text-slate-600 font-bold">
            {{ $initials ?: '?' }}
        </span>
    @else
        <span class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-700 font-bold">
            {{ $initials ?: '?' }}
        </span>
    @endif
</div>

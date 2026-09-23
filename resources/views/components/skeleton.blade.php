@props([
    'type' => 'line', // line, card, avatar, block
    'lines' => 1,
])

@if($type === 'avatar')
    <div {{ $attributes->merge(['class' => 'h-12 w-12 rounded-full bg-slate-200 animate-pulse']) }}></div>
@elseif($type === 'card')
    <div {{ $attributes->merge(['class' => 'rounded-2xl border border-slate-200/80 p-5 bg-white animate-pulse space-y-4 shadow-xs']) }}>
        <div class="h-4 bg-slate-200 rounded-md w-1/3"></div>
        <div class="space-y-2">
            <div class="h-3 bg-slate-100 rounded-md w-full"></div>
            <div class="h-3 bg-slate-100 rounded-md w-4/5"></div>
        </div>
        <div class="h-8 bg-slate-100 rounded-xl w-1/4"></div>
    </div>
@elseif($type === 'block')
    <div {{ $attributes->merge(['class' => 'rounded-xl bg-slate-200 animate-pulse h-32 w-full']) }}></div>
@else
    <div class="space-y-2.5">
        @for($i = 0; $i < $lines; $i++)
            <div {{ $attributes->merge(['class' => 'h-3.5 bg-slate-200 rounded-md animate-pulse ' . ($i === $lines - 1 && $lines > 1 ? 'w-2/3' : 'w-full')]) }}></div>
        @endfor
    </div>
@endif

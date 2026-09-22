@props([
    'id',
    'title' => '',
    'size' => 'md', // sm, md, lg, xl
])

@php
    $maxWidth = match($size) {
        'sm' => 'max-w-md',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        default => 'max-w-lg',
    };
@endphp

<div id="{{ $id }}" data-modal="{{ $id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="{{ $id }}-title" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" data-modal-close="{{ $id }}"></div>

    {{-- Dialog Container --}}
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full {{ $maxWidth }} border border-slate-100">
            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h3 class="text-base font-bold text-slate-900" id="{{ $id }}-title">
                    {{ $title }}
                </h3>
                <button type="button" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 focus:outline-none" data-modal-close="{{ $id }}" aria-label="Close">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-6">
                {{ $slot }}
            </div>

            {{-- Footer if present --}}
            @if(isset($footer))
                <div class="flex items-center justify-end gap-3 bg-slate-50/80 px-6 py-4 border-t border-slate-100">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>

@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'required' => false,
    'hint' => null,
    'icon' => null,
])

<div class="w-full">
    @if($label)
        <label for="{{ $name }}" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
            {{ $label }}
            @if($required) <span class="text-brand-red">*</span> @endif
        </label>
    @endif

    <div class="relative rounded-xl shadow-2xs">
        @if($icon)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                {!! $icon !!}
            </div>
        @endif

        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge([
                'class' => 'block w-full rounded-xl border-slate-200 bg-white py-2.5 ' 
                . ($icon ? 'pl-10 ' : 'pl-3.5 ') 
                . 'pr-3.5 text-sm text-slate-900 placeholder:text-slate-400 border focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 focus:outline-none transition-colors duration-150 ' 
                . ($errors->has($name) ? 'border-status-error focus:border-status-error focus:ring-red-200' : '')
            ]) }}
        />
    </div>

    @error($name)
        <p class="mt-1.5 text-xs font-semibold text-status-error flex items-center gap-1">
            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ $message }}
        </p>
    @enderror

    @if($hint && !$errors->has($name))
        <p class="mt-1 text-xs text-slate-500">{{ $hint }}</p>
    @endif
</div>

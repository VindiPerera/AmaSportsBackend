@props([
    'headers' => [],
])

<div {{ $attributes->merge(['class' => 'w-full overflow-x-auto rounded-2xl border border-slate-200/80 bg-white shadow-xs']) }}>
    <table class="w-full text-left border-collapse text-sm">
        @if(!empty($headers))
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/75 text-xs font-bold uppercase tracking-wider text-slate-500">
                    @foreach($headers as $header)
                        <th scope="col" class="px-5 py-3.5">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody class="divide-y divide-slate-100 text-slate-700">
            {{ $slot }}
        </tbody>
    </table>
</div>

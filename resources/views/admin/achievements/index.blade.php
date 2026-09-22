@extends('admin.layouts.app')

@section('title', 'Achievements')

@section('content')
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-brand-charcoal tracking-tight">Achievements</h1>
            <p class="text-xs font-semibold text-slate-500 mt-0.5">Badges players unlock by crossing a stat threshold — displayed across athlete profiles and home screens.</p>
        </div>
        <x-button href="{{ route('admin.achievements.create') }}" variant="primary" size="md">
            + Create Achievement
        </x-button>
    </div>

    <div class="bg-amber-50/80 border border-amber-200 rounded-2xl px-5 py-3.5 mb-6 flex items-center gap-3">
        <span class="text-lg">🏆</span>
        <p class="text-xs text-amber-900 font-medium">
            <span class="font-extrabold">Cricket metrics active.</span>
            The Metric list offers stats currently tracked — new sports appear here automatically once their scoring engine is activated.
        </p>
    </div>

    @if ($achievements->isEmpty())
        <x-empty-state
            title="No achievements created yet"
            message="Create milestone badges that athletes can unlock and display on their public profile."
        >
            <x-button href="{{ route('admin.achievements.create') }}" variant="primary" size="sm">
                + Create First Achievement
            </x-button>
        </x-empty-state>
    @else
        <x-card class="p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-slate-50/80 border-b border-slate-100 text-slate-400 text-[10px] font-extrabold uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">Badge</th>
                            <th class="px-5 py-3.5">Metric</th>
                            <th class="px-5 py-3.5">Threshold</th>
                            <th class="px-5 py-3.5">Unlocked</th>
                            <th class="px-5 py-3.5">Posted</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @foreach ($achievements as $achievement)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 shadow-sm"
                                              style="background: linear-gradient(135deg, {{ $achievement->color }}, {{ $achievement->color }}cc);">
                                            <ion-icon name="{{ $achievement->icon }}" style="color: white; font-size: 20px;"></ion-icon>
                                        </span>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $achievement->title }}</p>
                                            @if ($achievement->description)
                                                <p class="text-xs text-slate-500 max-w-xs truncate">{{ $achievement->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ $achievement->sport?->name ?? '—' }} · {{ $achievement->metric_key }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                        {{ number_format($achievement->threshold) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 font-bold text-slate-700">{{ $achievement->unlocked_count }}</td>
                                <td class="px-5 py-4 font-bold text-slate-700">{{ $achievement->posted_count }}</td>
                                <td class="px-5 py-4">
                                    @if ($achievement->is_active)
                                        <x-badge variant="success" size="sm">Active</x-badge>
                                    @else
                                        <x-badge variant="neutral" size="sm">Inactive</x-badge>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <x-button href="{{ route('admin.achievements.edit', $achievement) }}" variant="secondary" size="sm">
                                            Edit
                                        </x-button>
                                        <form method="POST" action="{{ route('admin.achievements.destroy', $achievement) }}"
                                              onsubmit="return confirm('Delete &quot;{{ $achievement->title }}&quot;? Players who already unlocked or posted it keep it, but it can\'t be unlocked by anyone new.');">
                                            @csrf
                                            @method('DELETE')
                                            <x-button type="submit" variant="danger" size="sm">
                                                Delete
                                            </x-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>
    @endif
@endsection

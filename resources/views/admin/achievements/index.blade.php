@extends('admin.layouts.app')

@section('title', 'Achievements')

@section('content')
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Achievements</h1>
            <p class="text-sm text-slate-500 mt-0.5">Badges players unlock by crossing a stat threshold — they choose when to post an unlocked badge to their Profile and Home screen.</p>
        </div>
        <a href="{{ route('admin.achievements.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 text-sm font-bold shadow-lg shadow-blue-600/25 transition-all hover:scale-[1.02]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            + Create Achievement
        </a>
    </div>

    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200/70 rounded-2xl px-5 py-3.5 mb-6 flex items-center gap-3">
        <span class="text-lg">🏆</span>
        <p class="text-sm text-indigo-900">
            <span class="font-extrabold">Cricket-only for now.</span>
            The Metric list below only ever offers stats the app already knows how to evaluate — new sports appear here automatically once they're supported.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-xs font-bold text-slate-500 uppercase tracking-wider">
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
                <tbody class="divide-y divide-slate-100">
                    @forelse ($achievements as $achievement)
                        <tr class="hover:bg-slate-50/80 transition-colors">
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
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-extrabold bg-amber-50 text-amber-700">
                                    {{ number_format($achievement->threshold) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-bold text-slate-700">{{ $achievement->unlocked_count }}</td>
                            <td class="px-5 py-4 font-bold text-slate-700">{{ $achievement->posted_count }}</td>
                            <td class="px-5 py-4">
                                @if ($achievement->is_active)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-extrabold bg-slate-100 text-slate-500 border border-slate-200">Inactive</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.achievements.edit', $achievement) }}"
                                       class="px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.achievements.destroy', $achievement) }}"
                                          onsubmit="return confirm('Delete &quot;{{ $achievement->title }}&quot;? Players who already unlocked or posted it keep it, but it can\'t be unlocked by anyone new.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-sm text-slate-500">
                                No achievements yet — create one to let players unlock and post their first badge.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

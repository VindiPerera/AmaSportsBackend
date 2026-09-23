@extends('admin.layouts.app')

@section('title', 'Users Management')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Users Directory</h1>
            <p class="text-xs font-semibold text-slate-500 mt-0.5">View user accounts, contact info, roles, and subscription activity.</p>
        </div>
    </div>

    {{-- Filter / Search Form --}}
    <x-card class="p-5 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input
                    type="text"
                    name="q"
                    value="{{ $search }}"
                    placeholder="Search by name, email, or phone..."
                    class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all"
                >
            </div>
            <div class="sm:w-48">
                <select name="role" class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all">
                    <option value="">All Roles</option>
                    <option value="student" {{ $role === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="coach" {{ $role === 'coach' ? 'selected' : '' }}>Coach</option>
                    <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="super_admin" {{ $role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
            </div>
            <x-button type="submit" variant="primary" size="md">
                Filter Users
            </x-button>
            @if($search || $role)
                <x-button href="{{ route('admin.users.index') }}" variant="secondary" size="md">
                    Clear
                </x-button>
            @endif
        </form>
    </x-card>

    {{-- Users Table --}}
    <x-card class="p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 border-b border-slate-100 text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">User</th>
                        <th class="px-5 py-3.5">Email</th>
                        <th class="px-5 py-3.5">Phone</th>
                        <th class="px-5 py-3.5">Role</th>
                        <th class="px-5 py-3.5">Subscription</th>
                        <th class="px-5 py-3.5">Registered</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3.5 font-bold text-slate-900">
                                {{ $user->name }}
                            </td>
                            <td class="px-5 py-3.5 text-slate-600 font-medium">
                                {{ $user->email }}
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">
                                {{ $user->phone ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase
                                    {{ $user->isAdmin() ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($user->player && $user->player->subscriptions->isNotEmpty())
                                    @php $sub = $user->player->subscriptions->first(); @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $sub->status === 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $sub->status }} (${{ $sub->amount }})
                                    </span>
                                @else
                                    <span class="text-slate-400 font-semibold text-[11px]">Free Tier</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-slate-500 font-medium">
                                {{ $user->created_at?->format('M j, Y') ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400 font-semibold">
                                No users found matching your search criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection

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
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input
                    type="text"
                    name="q"
                    value="{{ $search }}"
                    placeholder="Search by name, email, or phone..."
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0366D6]/40 focus:border-[#0366D6]"
                >
            </div>
            <div class="sm:w-48">
                <select name="role" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0366D6]/40 focus:border-[#0366D6]">
                    <option value="">All Roles</option>
                    <option value="student" {{ $role === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="coach" {{ $role === 'coach' ? 'selected' : '' }}>Coach</option>
                    <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="super_admin" {{ $role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0366D6] hover:bg-blue-700 text-white text-xs font-extrabold shadow-xs transition-all">
                Filter Users
            </button>
            @if($search || $role)
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-all text-center">
                    Clear
                </a>
            @endif
        </form>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
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

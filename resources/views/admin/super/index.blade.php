@extends('admin.layouts.app')

@section('title', 'Super Admin')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Super Admin Overview</h1>
        <p class="text-xs font-semibold text-slate-500 mt-0.5">Platform-wide overview of subscriptions, live-stream VIP unlocks, registered users, and client match organisers.</p>
    </div>

    <div class="space-y-6">
        {{-- Payments --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-xs font-black uppercase tracking-wider text-slate-900">
                    Recent Transactions
                </h2>
                <a href="{{ route('admin.payments.index') }}" class="text-xs font-bold text-brand-red hover:underline">
                    View All Transactions →
                </a>
            </div>

            <div class="px-5 pb-2">
                <p class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider mb-2">Subscriptions ($10/yr)</p>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs mb-4">
                        <thead>
                            <tr class="text-left text-[10px] font-extrabold uppercase text-slate-400 border-b border-slate-100">
                                <th class="py-2 pr-3">Player</th>
                                <th class="py-2 pr-3">Amount</th>
                                <th class="py-2 pr-3">Status</th>
                                <th class="py-2 pr-3">Starts</th>
                                <th class="py-2 pr-3">Expires</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($subscriptions as $sub)
                                <tr>
                                    <td class="py-2 pr-3 font-semibold text-slate-700">{{ $sub->player?->full_name ?? $sub->player?->user?->email ?? '—' }}</td>
                                    <td class="py-2 pr-3 font-extrabold text-slate-900">{{ $sub->currency }} {{ $sub->amount }}</td>
                                    <td class="py-2 pr-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase
                                            {{ $sub->status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $sub->status }}
                                        </span>
                                    </td>
                                    <td class="py-2 pr-3 text-slate-600">{{ $sub->starts_at?->format('Y-m-d') ?? '—' }}</td>
                                    <td class="py-2 pr-3 text-slate-600">{{ $sub->expires_at?->format('Y-m-d') ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-4 text-center text-slate-400 font-semibold">No subscriptions yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <p class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider mb-2">Live-Stream Unlocks ($5/match)</p>
                <div class="overflow-x-auto pb-5">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="text-left text-[10px] font-extrabold uppercase text-slate-400 border-b border-slate-100">
                                <th class="py-2 pr-3">Match</th>
                                <th class="py-2 pr-3">Paid By</th>
                                <th class="py-2 pr-3">Amount</th>
                                <th class="py-2 pr-3">Status</th>
                                <th class="py-2 pr-3">Purchased</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($streamPayments as $payment)
                                <tr>
                                    <td class="py-2 pr-3 font-semibold text-slate-700">
                                        {{ $payment->match?->homeTeam?->name }} vs {{ $payment->match?->awayTeam?->name }}
                                    </td>
                                    <td class="py-2 pr-3 text-slate-600">{{ $payment->paidByUser?->email ?? '—' }}</td>
                                    <td class="py-2 pr-3 font-extrabold text-slate-900">{{ $payment->currency }} {{ $payment->amount }}</td>
                                    <td class="py-2 pr-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase
                                            {{ $payment->status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $payment->status }}
                                        </span>
                                    </td>
                                    <td class="py-2 pr-3 text-slate-600">{{ $payment->purchased_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-4 text-center text-slate-400 font-semibold">No live-stream payments yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Users --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-xs font-black uppercase tracking-wider text-slate-900">
                    Users <span class="text-slate-400 font-semibold normal-case">({{ $users->count() }})</span>
                </h2>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-brand-red hover:underline">
                    View Full Directory →
                </a>
            </div>
            <div class="overflow-x-auto px-6 py-3">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-left text-[10px] font-extrabold uppercase text-slate-400 border-b border-slate-100">
                            <th class="py-2.5 pr-3">Name</th>
                            <th class="py-2.5 pr-3">Email</th>
                            <th class="py-2.5 pr-3">Phone</th>
                            <th class="py-2.5 pr-3">Role</th>
                            <th class="py-2.5 pr-3">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-2.5 pr-3 font-bold text-slate-800">{{ $user->name }}</td>
                                <td class="py-2.5 pr-3 text-slate-600">{{ $user->email }}</td>
                                <td class="py-2.5 pr-3 text-slate-600">{{ $user->phone ?? '—' }}</td>
                                <td class="py-2.5 pr-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="py-2.5 pr-3 text-slate-500">{{ $user->created_at?->format('M j, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-6 text-center text-slate-400 font-semibold">No users registered yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Clients (admin accounts who create matches) --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-xs font-black uppercase tracking-wider text-slate-900">
                    Clients <span class="text-slate-400 font-semibold normal-case">({{ $clients->count() }}) — Match Organisers</span>
                </h2>
            </div>
            <div class="overflow-x-auto px-6 py-3">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-left text-[10px] font-extrabold uppercase text-slate-400 border-b border-slate-100">
                            <th class="py-2.5 pr-3">Name</th>
                            <th class="py-2.5 pr-3">Email</th>
                            <th class="py-2.5 pr-3">Phone</th>
                            <th class="py-2.5 pr-3">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($clients as $client)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-2.5 pr-3 font-bold text-slate-800">{{ $client->name }}</td>
                                <td class="py-2.5 pr-3 text-slate-600">{{ $client->email }}</td>
                                <td class="py-2.5 pr-3 text-slate-600">{{ $client->phone ?? '—' }}</td>
                                <td class="py-2.5 pr-3 text-slate-500">{{ $client->created_at?->format('M j, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-6 text-center text-slate-400 font-semibold">No clients yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

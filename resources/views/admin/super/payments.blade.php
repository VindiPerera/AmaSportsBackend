@extends('admin.layouts.app')

@section('title', 'Payments & Purchases')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Payments &amp; Purchases</h1>
            <p class="text-xs font-semibold text-slate-500 mt-0.5">Track annual app subscriptions and VIP live-stream unlock purchases.</p>
        </div>
    </div>

    {{-- Type Tabs --}}
    <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-1">
        <a href="{{ route('admin.payments.index', ['type' => 'all']) }}"
           class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all {{ $type === 'all' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            All Transactions
        </a>
        <a href="{{ route('admin.payments.index', ['type' => 'subscriptions']) }}"
           class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all {{ $type === 'subscriptions' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            App Subscriptions ($10/yr)
        </a>
        <a href="{{ route('admin.payments.index', ['type' => 'stream']) }}"
           class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all {{ $type === 'stream' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Live Stream Unlocks ($5/match)
        </a>
    </div>

    <div class="space-y-6">

        @if($type === 'all' || $type === 'subscriptions')
        {{-- App Subscriptions Table --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-extrabold text-slate-900 tracking-wider uppercase">
                        Annual App Subscriptions ($10/year)
                    </h2>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Player subscription purchases granting full analysis & sport editing features.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-blue-50 text-[#0366D6] text-[10px] font-black uppercase">
                    {{ $subscriptions->count() }} Total
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-50 border-b border-slate-100 text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">
                        <tr>
                            <th class="px-5 py-3">Player / User</th>
                            <th class="px-5 py-3">Amount</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">PayPal Order</th>
                            <th class="px-5 py-3">Starts At</th>
                            <th class="px-5 py-3">Expires At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($subscriptions as $sub)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-5 py-3 font-bold text-slate-800">
                                    {{ $sub->player?->full_name ?? $sub->player?->user?->name ?? '—' }}
                                    <div class="text-[11px] text-slate-500 font-normal">{{ $sub->player?->user?->email }}</div>
                                </td>
                                <td class="px-5 py-3 font-black text-slate-900">
                                    {{ $sub->currency }} {{ $sub->amount }}
                                </td>
                                <td class="px-5 py-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $sub->status === 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $sub->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 font-mono text-[11px] text-slate-600">
                                    {{ $sub->paypal_order_id ?? 'N/A' }}
                                </td>
                                <td class="px-5 py-3 text-slate-600 font-medium">
                                    {{ $sub->starts_at?->format('Y-m-d H:i') ?? '—' }}
                                </td>
                                <td class="px-5 py-3 text-slate-600 font-medium">
                                    {{ $sub->expires_at?->format('Y-m-d H:i') ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-6 text-center text-slate-400 font-semibold">
                                    No subscription transactions recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if($type === 'all' || $type === 'stream')
        {{-- Live Stream Unlocks Table --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-extrabold text-slate-900 tracking-wider uppercase">
                        Live Stream VIP Unlocks ($5/match)
                    </h2>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Per-match VIP stream access purchases by viewers and players.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-[10px] font-black uppercase">
                    {{ $streamPayments->count() }} Total
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-50 border-b border-slate-100 text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">
                        <tr>
                            <th class="px-5 py-3">Match Fixture</th>
                            <th class="px-5 py-3">Purchased By</th>
                            <th class="px-5 py-3">Amount</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">PayPal Order</th>
                            <th class="px-5 py-3">Purchased Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($streamPayments as $payment)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-5 py-3 font-bold text-slate-800">
                                    {{ $payment->match?->homeTeam?->name }} <span class="text-slate-400 font-normal">vs</span> {{ $payment->match?->awayTeam?->name }}
                                </td>
                                <td class="px-5 py-3 text-slate-700 font-semibold">
                                    {{ $payment->paidByUser?->name ?? $payment->paidByUser?->email ?? 'Admin/Guest' }}
                                    @if($payment->paidByUser?->email)
                                        <div class="text-[11px] text-slate-400 font-normal">{{ $payment->paidByUser->email }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3 font-black text-slate-900">
                                    {{ $payment->currency }} {{ $payment->amount }}
                                </td>
                                <td class="px-5 py-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $payment->status === 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $payment->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 font-mono text-[11px] text-slate-600">
                                    {{ $payment->paypal_order_id ?? 'N/A' }}
                                </td>
                                <td class="px-5 py-3 text-slate-600 font-medium">
                                    {{ $payment->purchased_at?->format('Y-m-d H:i') ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-6 text-center text-slate-400 font-semibold">
                                    No live stream VIP purchases recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
@endsection

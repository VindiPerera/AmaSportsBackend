@extends('public.layouts.app')
@section('title', 'Transparent Pricing')
@section('meta_description', 'AmaSports pricing: annual athlete profile subscription and per-match live-stream unlock. Clear, one-time payments with no surprise auto-renewals.')

@section('content')
{{-- Hero Section --}}
<section class="relative overflow-hidden py-16 sm:py-20 bg-gradient-to-b from-slate-50 via-white to-[#F8F9FB] border-b border-slate-200/70">
    <div class="absolute inset-0 bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] [background-size:24px_24px] opacity-40 pointer-events-none"></div>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 relative text-center">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-900 text-xs font-black uppercase tracking-wider mb-4 shadow-xs">
            <span>⭐</span> Simple, One-Time Pricing
        </div>
        <h1 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight mb-4">
            Invest in your sports career. <br class="hidden sm:inline" />
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red via-brand-red to-brand-gold">Zero hidden charges.</span>
        </h1>
        <p class="text-base sm:text-lg text-slate-600 font-medium max-w-2xl mx-auto leading-relaxed">
            AmaSports offers transparent, non-recurring pricing for athlete profile features and VIP live video streaming. All payments are securely processed through PayHere.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-2.5 mt-6 text-xs font-bold text-slate-500">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200 shadow-xs">
                <span>💵</span> Prices in USD
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200 shadow-xs">
                <span>🔒</span> PayHere 256-bit Encrypted
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200 shadow-xs">
                <span>🚫</span> No Auto-Renewals
            </span>
        </div>
    </div>
</section>

{{-- Plans Section --}}
<section class="max-w-5xl mx-auto px-4 sm:px-6 py-12 sm:py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch">
        
        {{-- Plan 1: Player Annual Subscription --}}
        <div class="bg-white rounded-3xl border-2 border-brand-gold/60 p-8 sm:p-10 shadow-xl shadow-amber-500/5 relative flex flex-col justify-between">
            <div class="absolute -top-3.5 right-8">
                <span class="px-3.5 py-1 rounded-full bg-gradient-to-r from-brand-gold to-amber-500 text-slate-950 text-xs font-black uppercase tracking-wider shadow-sm">
                    Most Popular
                </span>
            </div>

            <div>
                <div class="inline-flex items-center gap-1.5 text-xs font-black uppercase tracking-wider text-amber-700 bg-amber-50 px-3 py-1 rounded-full mb-3">
                    <span>👑</span> Athlete Profile
                </div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">Annual All-Sport Access</h3>
                <p class="text-xs text-slate-500 font-semibold mt-1">Full access to your verified athlete digital passport</p>

                <div class="my-6">
                    <div class="flex items-baseline gap-2">
                        <span class="text-5xl font-black text-slate-900 tracking-tight">$10.00</span>
                        <span class="text-sm font-bold text-slate-500">/ year</span>
                    </div>
                    <p class="text-xs font-semibold text-slate-500 mt-2">
                        Country-specific pricing may apply. Exact localized price is always shown prior to checkout.
                    </p>
                </div>

                <div class="h-px bg-slate-100 my-6"></div>

                <ul class="space-y-3 text-sm text-slate-700 font-semibold">
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-black text-xs shrink-0 mt-0.5">✓</span>
                        <span>Register and manage <strong>unlimited sports</strong> on your athlete passport</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-black text-xs shrink-0 mt-0.5">✓</span>
                        <span>Comprehensive <strong>career statistics &amp; performance breakdown</strong></span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-black text-xs shrink-0 mt-0.5">✓</span>
                        <span>Shareable <strong>public athlete profile URL</strong> for scouts, coaches &amp; recruiters</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-black text-xs shrink-0 mt-0.5">✓</span>
                        <span>Full <strong>12 months of access</strong> from the date of purchase</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-black text-xs shrink-0 mt-0.5">✓</span>
                        <span><strong>No auto-renewal:</strong> you will never be charged without your direct consent</span>
                    </li>
                </ul>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100">
                <a href="{{ route('register') }}" class="w-full flex items-center justify-center gap-2 rounded-xl bg-brand-red hover:bg-red-700 text-white py-3.5 text-xs font-black uppercase tracking-wider shadow-lg shadow-red-600/25 transition-all hover:scale-[1.01]">
                    Create Free Profile &amp; Upgrade
                </a>
            </div>
        </div>

        {{-- Plan 2: Match Live Stream Unlock --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-10 shadow-soft flex flex-col justify-between">
            <div>
                <div class="inline-flex items-center gap-1.5 text-xs font-black uppercase tracking-wider text-blue-700 bg-blue-50 px-3 py-1 rounded-full mb-3">
                    <span>🎥</span> Fans &amp; Spectators
                </div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">Match VIP Stream Unlock</h3>
                <p class="text-xs text-slate-500 font-semibold mt-1">High-definition live video broadcast access</p>

                <div class="my-6">
                    <div class="flex items-baseline gap-2">
                        <span class="text-5xl font-black text-slate-900 tracking-tight">$5.00</span>
                        <span class="text-sm font-bold text-slate-500">/ match</span>
                    </div>
                    <p class="text-xs font-semibold text-slate-500 mt-2">
                        Live scoreboards and ball-by-ball updates remain 100% free for everyone.
                    </p>
                </div>

                <div class="h-px bg-slate-100 my-6"></div>

                <ul class="space-y-3 text-sm text-slate-700 font-semibold">
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-black text-xs shrink-0 mt-0.5">✓</span>
                        <span>Watch the <strong>official live video stream</strong> for a fixture</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-black text-xs shrink-0 mt-0.5">✓</span>
                        <span>Instant access across mobile devices and web desktop</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-black text-xs shrink-0 mt-0.5">✓</span>
                        <span>Single match pass — <strong>no recurring subscription</strong> or commitments</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-black text-xs shrink-0 mt-0.5">✓</span>
                        <span>Direct contribution supporting tournament organizers &amp; local sports</span>
                    </li>
                </ul>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100">
                <a href="{{ route('public.matches') }}" class="w-full flex items-center justify-center gap-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white py-3.5 text-xs font-black uppercase tracking-wider shadow-sm transition-all hover:scale-[1.01]">
                    Explore Live Matches
                </a>
            </div>
        </div>

    </div>

    {{-- Free trial banner --}}
    <div class="mt-8 rounded-2xl bg-emerald-50/80 border border-emerald-200 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <span class="text-2xl">🎁</span>
            <div>
                <h4 class="text-sm font-black text-emerald-950 uppercase tracking-wider">10-Day Free Athlete Trial</h4>
                <p class="text-xs font-semibold text-emerald-800 mt-0.5">
                    New athletes receive an unconditional 10-day trial upon sign-up. Experience full multi-sport profile building without entering payment information.
                </p>
            </div>
        </div>
        <a href="{{ route('register') }}" class="shrink-0 px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-black uppercase tracking-wider transition-all">
            Start Free Trial
        </a>
    </div>

    {{-- Good to know card --}}
    <div class="mt-12 bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-10 shadow-soft">
        <h3 class="text-lg font-black text-slate-900 tracking-tight mb-4">Payment Confidence &amp; Guarantees</h3>
        <ul class="space-y-3 text-xs sm:text-sm text-slate-600 font-medium leading-relaxed">
            <li class="flex items-start gap-2.5">
                <span class="text-brand-red font-bold">›</span>
                <span>All transactions are handled exclusively by <strong class="text-slate-800">PayHere (Pvt) Ltd</strong>. AmaSports never receives or stores your credit/debit card numbers or bank credentials.</span>
            </li>
            <li class="flex items-start gap-2.5">
                <span class="text-brand-red font-bold">›</span>
                <span>Both products are strictly <strong class="text-slate-800">one-time purchases</strong>. There are no automatic monthly or yearly renewals on our platform.</span>
            </li>
            <li class="flex items-start gap-2.5">
                <span class="text-brand-red font-bold">›</span>
                <span>If a transaction was mistakenly duplicated or a stream fails to air, refer to our <a href="{{ route('refund-policy') }}" class="text-brand-red font-bold hover:underline">Refund Policy</a> for prompt resolution.</span>
            </li>
            <li class="flex items-start gap-2.5">
                <span class="text-brand-red font-bold">›</span>
                <span>Review our <a href="{{ route('terms') }}" class="text-brand-red font-bold hover:underline">Terms &amp; Conditions</a> and <a href="{{ route('privacy-policy') }}" class="text-brand-red font-bold hover:underline">Privacy Policy</a> for complete legal details.</span>
            </li>
        </ul>

        <div class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap items-center gap-3">
            <span class="px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">🔒 PayHere Secured</span>
            <span class="px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">🛡️ PCI-DSS Level 1 Compliant</span>
            <span class="px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">🏛️ Central Bank Regulated</span>
        </div>
    </div>
</section>
@endsection

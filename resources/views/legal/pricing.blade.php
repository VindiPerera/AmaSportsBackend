@extends('public.layouts.app')
@section('title', 'Pricing')
@section('meta_description', 'AmaSports pricing: the annual player subscription and per-match live-stream unlock, both paid securely through PayHere. One-time purchases, no auto-renewal.')
@section('content')
<style>
    .pricing-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }
    @media (max-width: 720px) {
        .pricing-grid { grid-template-columns: 1fr; }
    }
    .plan-card {
        padding: 1.75rem 1.625rem;
        border-radius: 1rem;
    }
    .plan-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.6875rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #818cf8;
        background: rgba(99,102,241,0.12);
        border: 1px solid rgba(99,102,241,0.25);
        padding: 0.3rem 0.75rem;
        border-radius: 2rem;
        margin-bottom: 0.875rem;
    }
    .plan-badge.gold {
        color: #f59e0b;
        background: rgba(245,158,11,0.1);
        border-color: rgba(245,158,11,0.25);
    }
    .plan-name {
        font-size: 1.125rem;
        font-weight: 800;
        color: #fff;
        margin: 0 0 0.375rem;
    }
    .plan-price {
        font-size: 2.25rem;
        font-weight: 900;
        letter-spacing: -0.03em;
        color: #fff;
        margin: 0.375rem 0 0.25rem;
        line-height: 1;
    }
    .plan-price small {
        font-size: 0.875rem;
        font-weight: 700;
        color: rgba(148,163,184,0.75);
    }
    .plan-note {
        font-size: 0.78125rem;
        color: rgba(148,163,184,0.65);
        margin: 0 0 1.125rem;
        line-height: 1.5;
    }
    .plan-divider {
        border: none;
        border-top: 1px solid rgba(255,255,255,0.08);
        margin: 0 0 1rem;
    }
    .plan-features {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .plan-features li {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        font-size: 0.84375rem;
        color: rgba(148,163,184,0.85);
        line-height: 1.5;
    }
    .plan-features li .check {
        color: #10b981;
        font-weight: 900;
        flex: none;
        margin-top: 0.05rem;
    }
    .trial-strip {
        margin-top: 1.25rem;
        background: rgba(16,185,129,0.07);
        border: 1px solid rgba(16,185,129,0.2);
        color: #6ee7b7;
        border-radius: 0.875rem;
        padding: 0.875rem 1.125rem;
        font-size: 0.84375rem;
        line-height: 1.7;
    }
    .trial-strip strong { color: #a7f3d0; }
    .notes-card { padding: 1.5rem 1.75rem; margin-top: 2rem; }
    .notes-card h2 {
        font-size: 0.9375rem;
        font-weight: 800;
        color: #fff;
        margin: 0 0 0.75rem;
    }
    .notes-card ul { margin: 0; padding-left: 1.25rem; }
    .notes-card li {
        font-size: 0.84375rem;
        color: rgba(148,163,184,0.85);
        line-height: 1.75;
        margin-bottom: 0.375rem;
    }
    .notes-card a { color: #f59e0b; font-weight: 600; }
    .pay-badges {
        margin-top: 1.125rem;
        display: flex;
        align-items: center;
        gap: 0.625rem;
        flex-wrap: wrap;
    }
    .pay-badge {
        border: 1px solid rgba(255,255,255,0.1);
        background: rgba(255,255,255,0.04);
        border-radius: 0.5rem;
        padding: 0.3rem 0.75rem;
        font-size: 0.71875rem;
        font-weight: 700;
        color: rgba(203,213,225,0.7);
    }
    .meta-row {
        margin-top: 1.125rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.625rem;
        font-size: 0.75rem;
        color: rgba(148,163,184,0.75);
    }
    .meta-pill {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 0.5rem;
        padding: 0.375rem 0.625rem;
        font-weight: 600;
    }
    .highlight-card {
        border: 1px solid rgba(245,158,11,0.25) !important;
        box-shadow: 0 0 0 1px rgba(245,158,11,0.08), 0 8px 32px rgba(245,158,11,0.06);
    }
</style>

{{-- Hero --}}
<section style="position: relative; overflow: hidden; padding: 3.5rem 1.5rem 1.5rem;">
    <div style="position: absolute; inset: 0; background: radial-gradient(ellipse 70% 50% at 50% 0%, rgba(245,158,11,0.08) 0%, transparent 70%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1280px; margin: 0 auto;">
        <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.25); border-radius: 2rem; padding: 0.375rem 1rem; font-size: 0.75rem; font-weight: 700; color: #f59e0b; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 1.25rem;">
            Pricing
        </div>
        <h1 style="font-weight: 900; font-size: clamp(1.875rem, 4vw, 2.75rem); color: #fff; line-height: 1.1; letter-spacing: -0.03em; margin-bottom: 1rem;">Simple, one-time pricing</h1>
        <p style="font-size: 0.9375rem; color: rgba(148,163,184,0.85); line-height: 1.75; max-width: 68ch;">
            AmaSports charges for two things: an annual player subscription and single-match live-stream unlocks.
            Both are one-time purchases — there's no auto-renewing subscription — paid securely through our
            payment partner <strong style="color:#e2e8f0;">PayHere</strong>.
        </p>
        <div class="meta-row">
            <span class="meta-pill">Prices shown in USD</span>
            <span class="meta-pill">Payments processed by PayHere</span>
            <span class="meta-pill">Contact: alex@amaxlk.com</span>
        </div>
    </div>
</section>

<section style="max-width: 1280px; margin: 0 auto; padding: 1.5rem 1.5rem 5rem;">

    {{-- Plan cards --}}
    <div class="pricing-grid">

        {{-- Player Subscription --}}
        <div class="glass-card plan-card highlight-card">
            <span class="plan-badge gold">⭐ Player Subscription</span>
            <p class="plan-name">Annual Sport Access</p>
            <p class="plan-price">$10.00 <small>/ year</small></p>
            <p class="plan-note">Price may be adjusted for your country — the exact amount is always shown before you pay.</p>
            <hr class="plan-divider">
            <ul class="plan-features">
                <li><span class="check">✓</span> Add unlimited sports to your player profile</li>
                <li><span class="check">✓</span> Edit and update the sport profiles you've registered</li>
                <li><span class="check">✓</span> Access to the Analysis tab and career statistics</li>
                <li><span class="check">✓</span> Valid for 1 year from purchase date</li>
                <li><span class="check">✓</span> No auto-renewal — you're never charged again automatically</li>
            </ul>
        </div>

        {{-- Live Stream Unlock --}}
        <div class="glass-card plan-card">
            <span class="plan-badge">🎥 Live Stream</span>
            <p class="plan-name">Match Unlock</p>
            <p class="plan-price">$5.00 <small>/ match</small></p>
            <p class="plan-note">Live scoring is always free — this only unlocks that match's video stream.</p>
            <hr class="plan-divider">
            <ul class="plan-features">
                <li><span class="check">✓</span> Watch the live video stream for one specific match</li>
                <li><span class="check">✓</span> One-time purchase — no recurring charge</li>
                <li><span class="check">✓</span> Ball-by-ball live scores remain free for everyone</li>
            </ul>
        </div>

    </div>

    {{-- Free trial strip --}}
    <div class="trial-strip">
        <strong>Free trial:</strong> new players get one free 10-day trial before any subscription charge applies —
        you're only billed if you choose to continue after the trial ends. No card required to start a trial.
    </div>

    {{-- Good to know --}}
    <div class="glass-card notes-card">
        <h2>Good to know</h2>
        <ul>
            <li>All payments are processed by <strong style="color:#e2e8f0;">PayHere (Pvt) Ltd</strong>; AmaX never sees or stores your full card, bank, or wallet details.</li>
            <li>Both products are <strong style="color:#e2e8f0;">one-time, non-recurring purchases</strong> — we never charge you again automatically when a subscription year or a match unlock expires.</li>
            <li>Prices may include applicable taxes and can change over time; a change never affects something you've already paid for.</li>
            <li>If a charge was duplicated or your access was never activated, you may be eligible for a refund — see our <a href="{{ route('refund-policy') }}">Refund Policy</a> for details. Approved refunds are always returned to the original payment method.</li>
            <li>For the full terms of use see our <a href="{{ route('terms') }}">Terms &amp; Conditions</a> and <a href="{{ route('privacy-policy') }}">Privacy Policy</a>.</li>
        </ul>
        <div class="pay-badges">
            <span class="pay-badge">🔒 PayHere Secured</span>
            <span class="pay-badge">PCI-DSS Compliant</span>
            <span class="pay-badge">Regulated by Central Bank of Sri Lanka</span>
        </div>
    </div>

</section>
@endsection

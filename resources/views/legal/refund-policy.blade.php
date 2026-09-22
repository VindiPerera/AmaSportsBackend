@extends('public.layouts.app')
@section('title', 'Refund Policy')
@section('meta_description', 'AmaSports refund policy for player subscriptions and live-stream unlocks paid through PayHere — eligibility, timelines, and how refunds are returned to your original payment method.')
@section('content')
<style>
    .doc-layout { display: grid; grid-template-columns: 260px 1fr; gap: 2rem; align-items: start; }
    @media (max-width: 900px) { .doc-layout { grid-template-columns: 1fr; } }
    .doc-toc { position: sticky; top: 5rem; padding: 1.25rem; background: #ffffff; border: 1px solid rgba(226, 232, 240, 0.8); border-radius: 1.25rem; box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05); }
    .doc-toc h2 { font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin: 0 0 0.75rem; font-weight: 800; }
    .doc-toc ol { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 2px; }
    .doc-toc a { display: block; text-decoration: none; color: #475569; font-size: 0.8125rem; font-weight: 600; padding: 0.4rem 0.625rem; border-radius: 0.625rem; transition: all 0.15s ease; }
    .doc-toc a:hover { background: #fee2e2; color: #EC1F24; }
    .doc-main { padding: 2rem; background: #ffffff; border: 1px solid rgba(226, 232, 240, 0.8); border-radius: 1.25rem; box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05); }
    .doc-main section { padding-top: 2rem; border-top: 1px solid #f1f5f9; margin-top: 2rem; }
    .doc-main section:first-of-type { border-top: none; margin-top: 0; padding-top: 0; }
    .doc-main h2 { font-size: 1.25rem; font-weight: 900; letter-spacing: -0.02em; color: #0f172a; display: flex; align-items: center; gap: 0.625rem; scroll-margin-top: 5rem; margin: 0 0 0.875rem; }
    .doc-main h2 .num { display: inline-flex; align-items: center; justify-content: center; width: 1.75rem; height: 1.75rem; border-radius: 0.5rem; background: #fee2e2; color: #EC1F24; font-size: 0.75rem; font-weight: 900; flex: none; }
    .doc-main h3 { font-size: 0.9375rem; font-weight: 800; margin: 1.25rem 0 0.5rem; color: #1e293b; }
    .doc-main p, .doc-main li { font-size: 0.90625rem; line-height: 1.75; color: #475569; }
    .doc-main ul { padding-left: 1.25rem; margin: 0.625rem 0; }
    .doc-main li { margin-bottom: 0.375rem; }
    .doc-main strong { color: #0f172a; }
    .doc-main a { color: #EC1F24; font-weight: 700; text-decoration: underline; text-underline-offset: 2px; }
    .doc-table { width: 100%; border-collapse: collapse; margin: 1rem 0; font-size: 0.84375rem; }
    .doc-table th, .doc-table td { text-align: left; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; vertical-align: top; line-height: 1.6; color: #475569; }
    .doc-table th { background: #f8fafc; color: #0f172a; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em; font-weight: 800; }
    .doc-callout { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 0.875rem; padding: 1rem 1.25rem; font-size: 0.84375rem; color: #1e40af; line-height: 1.7; margin: 1rem 0; }
    .doc-callout strong { color: #1e3a8a; }
    .doc-callout.green { background: #ecfdf5; border-color: #a7f3d0; color: #065f46; }
    .doc-callout.green strong { color: #047857; }
    .doc-offices { display: grid; grid-template-columns: 1fr 1fr; gap: 0.875rem; margin-top: 0.875rem; }
    @media (max-width: 620px) { .doc-offices { grid-template-columns: 1fr; } }
    .doc-office-card { border: 1px solid #e2e8f0; border-radius: 0.875rem; padding: 1rem 1.25rem; background: #f8fafc; }
    .doc-office-card .flag { font-size: 0.8125rem; font-weight: 800; color: #EC1F24; margin-bottom: 0.375rem; }
    .doc-office-card p { margin: 0.125rem 0; font-size: 0.84375rem; color: #475569; }
    .meta-row { margin-top: 1.25rem; display: flex; flex-wrap: wrap; gap: 0.625rem; font-size: 0.75rem; color: #64748b; }
    .meta-pill { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 0.625rem; padding: 0.375rem 0.75rem; font-weight: 700; color: #475569; box-shadow: 0 1px 2px rgba(0,0,0,0.03); }
</style>

{{-- Hero --}}
<section class="relative overflow-hidden py-14 sm:py-16 bg-gradient-to-b from-slate-50 via-white to-[#F8F9FB] border-b border-slate-200/70">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 border border-red-200 text-brand-red text-xs font-black uppercase tracking-wider mb-3">
            Legal &amp; Compliance
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-3">Refund Policy</h1>
        <p class="text-sm sm:text-base text-slate-600 font-medium max-w-3xl leading-relaxed">
            AmaSports sells digital products only — annual player subscriptions and single-match live-stream unlocks — paid
            through our payment partner <strong class="text-slate-800">PayHere</strong>. This policy explains when you're
            eligible for a refund, how to request one, and how it is paid back to you.
        </p>
        <div class="meta-row">
            <span class="meta-pill">Effective date: 10 September 2026</span>
            <span class="meta-pill">Applies to: Subscriptions &amp; live-stream unlocks</span>
            <span class="meta-pill">Contact: alex@amaxlk.com</span>
        </div>
    </div>
</section>

<section class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
    <div class="doc-layout">

        <nav class="glass-card doc-toc">
            <h2>On this page</h2>
            <ol>
                <li><a href="#overview">1. Overview</a></li>
                <li><a href="#refund-method">2. How refunds are paid</a></li>
                <li><a href="#eligible">3. When you're eligible</a></li>
                <li><a href="#not-eligible">4. When you're not eligible</a></li>
                <li><a href="#trial">5. Free trial</a></li>
                <li><a href="#how-to-request">6. How to request a refund</a></li>
                <li><a href="#timeline">7. Processing timeline</a></li>
                <li><a href="#changes">8. Changes to this policy</a></li>
                <li><a href="#contact">9. Contact us</a></li>
            </ol>
        </nav>

        <main class="glass-card doc-main">

            <section id="overview">
                <h2><span class="num">1</span> Overview</h2>
                <p>
                    Everything AmaX Ltd. ("<strong>AmaX</strong>", "<strong>we</strong>", "<strong>us</strong>", or
                    "<strong>our</strong>") sells on the AmaSports platform is a digital product delivered instantly to your
                    account — there is no physical shipping, and nothing to return by post. We currently offer two paid
                    products:
                </p>
                <table class="doc-table">
                    <thead>
                        <tr><th>Product</th><th>Price</th><th>What you get</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Player subscription</td>
                            <td>USD 10.00 / year (may vary by country — see <a href="{{ route('pricing') }}">Pricing</a>)</td>
                            <td>One year of access to add sports and edit your player profile's sport data</td>
                        </tr>
                        <tr>
                            <td>Live-stream unlock</td>
                            <td>USD 5.00 / match</td>
                            <td>Access to that single match's live-stream video</td>
                        </tr>
                    </tbody>
                </table>
                <p>
                    Because access is granted immediately on successful payment, refunds are handled as described below
                    rather than through a returns process.
                </p>
            </section>

            <section id="refund-method">
                <h2><span class="num">2</span> How refunds are paid</h2>
                <div class="doc-callout green">
                    <strong>Any refund we approve is returned to the same payment method you used to pay — the original
                    card, bank account, or e‑wallet processed by PayHere at checkout.</strong> We do not pay refunds in
                    cash, to a different card or account, or by any other method. PayHere handles the actual reversal back
                    to your original payment method on our instruction.
                </div>
            </section>

            <section id="eligible">
                <h2><span class="num">3</span> When you're eligible for a refund</h2>
                <ul>
                    <li><strong>Duplicate or accidental charge</strong> — you were charged more than once for the same subscription period or the same match.</li>
                    <li><strong>Charged but not activated</strong> — payment was deducted by PayHere but your subscription or stream access was never activated on your account due to a fault on our side.</li>
                    <li><strong>Live-stream failure</strong> — you unlocked a match stream and it failed to become available for the match due to a technical fault we are responsible for (not, for example, your own internet connection or device).</li>
                    <li><strong>Unauthorised transaction</strong> — the payment was made without your authorisation (subject to verification).</li>
                    <li>Any other circumstance where a refund is required by the consumer-protection law that applies to you.</li>
                </ul>
            </section>

            <section id="not-eligible">
                <h2><span class="num">4</span> When you're not eligible for a refund</h2>
                <ul>
                    <li>You changed your mind after your subscription or live-stream access was already activated and usable;</li>
                    <li>The subscription period or the match has already passed and the access was available to you as purchased;</li>
                    <li>Poor stream quality or interruption caused by your own device, app, or internet connection rather than a fault on our side;</li>
                    <li>You no longer wish to use the Platform, or your account was suspended or terminated for breaching our <a href="{{ route('terms') }}">Terms &amp; Conditions</a>.</li>
                </ul>
            </section>

            <section id="trial">
                <h2><span class="num">5</span> Free trial</h2>
                <p>
                    New players get one free 10‑day trial before any subscription charge applies. Since the trial itself is
                    never charged, there is nothing to refund for it — you will only ever be billed once you choose to
                    subscribe after the trial ends.
                </p>
            </section>

            <section id="how-to-request">
                <h2><span class="num">6</span> How to request a refund</h2>
                <p>
                    Email <a href="mailto:alex@amaxlk.com">alex@amaxlk.com</a> within <strong>7 days</strong> of the charge with:
                </p>
                <ul>
                    <li>The email address on your AmaSports account;</li>
                    <li>The approximate date and amount of the charge;</li>
                    <li>The PayHere payment/order reference, if you have it (found in your payment confirmation email); and</li>
                    <li>A short description of the issue.</li>
                </ul>
                <p>We may ask a few follow-up questions to verify the charge before approving a refund.</p>
            </section>

            <section id="timeline">
                <h2><span class="num">7</span> Processing timeline</h2>
                <p>
                    We aim to review refund requests within <strong>3 business days</strong>. Once a refund is approved,
                    PayHere typically returns the funds to your <strong>original payment method</strong> within
                    <strong>5–10 business days</strong>, though the exact timing depends on your bank or card issuer and
                    is outside our control.
                </p>
            </section>

            <section id="changes">
                <h2><span class="num">8</span> Changes to this policy</h2>
                <p>
                    We may update this Refund Policy from time to time to reflect changes in our products, pricing, or
                    legal requirements. We will update the "Effective date" above whenever we do.
                </p>
            </section>

            <section id="contact">
                <h2><span class="num">9</span> Contact us</h2>
                <p>Questions about a refund? Reach us at:</p>
                <div class="doc-offices">
                    <div class="doc-office-card">
                        <div class="flag">🇱🇰 Head Office — Colombo</div>
                        <p><strong>AmaX Headquarters</strong></p>
                        <p>AmaX, Sutton Indoor Cricket Center</p>
                        <p>29 Maitland Place, Colombo 07, Sri Lanka</p>
                        <p>Phone: +94 75 220 6006</p>
                        <p>Email: <a href="mailto:alex@amaxlk.com">alex@amaxlk.com</a></p>
                    </div>
                    <div class="doc-office-card">
                        <div class="flag">🇮🇳 India Regional Hub — Moradabad</div>
                        <p><strong>AmaX India Regional Hub</strong></p>
                        <p>AmaX, Modern Public School</p>
                        <p>Delhi Road, Near Circuit House, Moradabad 244001, Uttar Pradesh, India</p>
                        <p>Phone: +91 95289 43413</p>
                        <p>Email: <a href="mailto:alex@amaxlk.com">alex@amaxlk.com</a></p>
                    </div>
                </div>
            </section>

        </main>
    </div>
</section>
@endsection

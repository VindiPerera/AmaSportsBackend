@extends('public.layouts.app')

@section('title', 'Terms & Conditions')
@section('meta_description', 'The terms that govern your use of the AmaSports app, website, and admin panel, including subscriptions, live-stream unlocks, and payments processed through PayHere.')

@section('content')

<style>
    .doc-layout { display: grid; grid-template-columns: 240px 1fr; gap: 2rem; align-items: start; }
    @media (max-width: 900px) { .doc-layout { grid-template-columns: 1fr; } }
    .doc-toc { position: sticky; top: 5rem; padding: 1.25rem; }
    .doc-toc h2 { font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(148,163,184,0.6); margin: 0 0 0.75rem; font-weight: 800; }
    .doc-toc ol { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 2px; }
    .doc-toc a { display: block; text-decoration: none; color: rgba(203,213,225,0.75); font-size: 0.8125rem; font-weight: 600; padding: 0.4rem 0.5rem; border-radius: 0.5rem; }
    .doc-toc a:hover { background: rgba(255,255,255,0.06); color: #fff; }
    .doc-main { padding: 0.5rem 2rem 2rem; }
    .doc-main section { padding-top: 1.75rem; border-top: 1px solid rgba(255,255,255,0.08); margin-top: 1.75rem; }
    .doc-main section:first-of-type { border-top: none; margin-top: 0.5rem; }
    .doc-main h2 { font-size: 1.1875rem; font-weight: 800; letter-spacing: -0.01em; color: #fff; display: flex; align-items: center; gap: 0.625rem; scroll-margin-top: 5rem; margin: 0 0 0.75rem; }
    .doc-main h2 .num { display: inline-flex; align-items: center; justify-content: center; width: 1.625rem; height: 1.625rem; border-radius: 0.5rem; background: rgba(99,102,241,0.15); color: #a5b4fc; font-size: 0.75rem; font-weight: 900; flex: none; }
    .doc-main h3 { font-size: 0.875rem; font-weight: 800; margin: 1.125rem 0 0.5rem; color: #e2e8f0; }
    .doc-main p, .doc-main li { font-size: 0.90625rem; line-height: 1.75; color: rgba(148,163,184,0.85); }
    .doc-main ul { padding-left: 1.25rem; margin: 0.625rem 0; }
    .doc-main li { margin-bottom: 0.375rem; }
    .doc-main strong { color: #f1f5f9; }
    .doc-main a { color: #f59e0b; font-weight: 600; }
    .doc-callout { background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.25); border-radius: 0.75rem; padding: 0.875rem 1rem; font-size: 0.84375rem; color: #c7d2fe; line-height: 1.7; margin: 0.875rem 0; }
    .doc-callout strong { color: #e0e7ff; }
    .doc-callout.warn { background: rgba(245,158,11,0.1); border-color: rgba(245,158,11,0.25); color: #fde68a; }
    .doc-callout.warn strong { color: #fef3c7; }
    .doc-offices { display: grid; grid-template-columns: 1fr 1fr; gap: 0.875rem; margin-top: 0.875rem; }
    @media (max-width: 620px) { .doc-offices { grid-template-columns: 1fr; } }
    .doc-office-card { border: 1px solid rgba(255,255,255,0.08); border-radius: 0.75rem; padding: 0.875rem 1rem; background: rgba(255,255,255,0.03); }
    .doc-office-card .flag { font-size: 0.8125rem; font-weight: 800; color: #a5b4fc; margin-bottom: 0.375rem; }
    .doc-office-card p { margin: 0.125rem 0; font-size: 0.84375rem; }
    .meta-row { margin-top: 1.125rem; display: flex; flex-wrap: wrap; gap: 0.625rem; font-size: 0.75rem; color: rgba(148,163,184,0.75); }
    .meta-pill { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 0.5rem; padding: 0.375rem 0.625rem; font-weight: 600; }
</style>

{{-- Hero --}}
<section style="position: relative; overflow: hidden; padding: 3.5rem 1.5rem 1.5rem;">
    <div style="position: absolute; inset: 0; background: radial-gradient(ellipse 70% 50% at 50% 0%, rgba(99,102,241,0.12) 0%, transparent 70%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 1280px; margin: 0 auto;">
        <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.25); border-radius: 2rem; padding: 0.375rem 1rem; font-size: 0.75rem; font-weight: 700; color: #818cf8; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 1.25rem;">
            Legal
        </div>
        <h1 style="font-weight: 900; font-size: clamp(1.875rem, 4vw, 2.75rem); color: #fff; line-height: 1.1; letter-spacing: -0.03em; margin-bottom: 1rem;">Terms &amp; Conditions</h1>
        <p style="font-size: 0.9375rem; color: rgba(148,163,184,0.85); line-height: 1.75; max-width: 68ch;">
            These Terms &amp; Conditions govern your use of the AmaSports mobile app, website, and
            admin/control panel (together, the "Platform"), and your purchase of any subscription or
            live-stream unlock through our payment partner <strong style="color:#e2e8f0;">PayHere</strong>.
            Please read them carefully before creating an account or making a payment.
        </p>
        <div class="meta-row">
            <span class="meta-pill">Effective date: 10 September 2026</span>
            <span class="meta-pill">Applies to: AmaSports app, website, admin panel</span>
            <span class="meta-pill">Contact: alex@amaxlk.com</span>
        </div>
    </div>
</section>

<section style="max-width: 1280px; margin: 0 auto; padding: 1.5rem 1.5rem 5rem;">
    <div class="doc-layout">
        <nav class="glass-card doc-toc">
            <h2>On this page</h2>
            <ol>
                <li><a href="#acceptance">1. Acceptance of terms</a></li>
                <li><a href="#who-we-are">2. Who we are</a></li>
                <li><a href="#eligibility">3. Eligibility &amp; accounts</a></li>
                <li><a href="#the-service">4. The service</a></li>
                <li><a href="#payments">5. Subscriptions, fees &amp; payments</a></li>
                <li><a href="#refunds">6. Refunds &amp; cancellations</a></li>
                <li><a href="#conduct">7. User content &amp; conduct</a></li>
                <li><a href="#player-data">8. Player profiles managed by coaches</a></li>
                <li><a href="#ip">9. Intellectual property</a></li>
                <li><a href="#third-party">10. Live streams &amp; third-party services</a></li>
                <li><a href="#disclaimer">11. Disclaimers</a></li>
                <li><a href="#liability">12. Limitation of liability</a></li>
                <li><a href="#termination">13. Suspension &amp; termination</a></li>
                <li><a href="#law">14. Governing law &amp; disputes</a></li>
                <li><a href="#changes">15. Changes to these terms</a></li>
                <li><a href="#contact">16. Contact us</a></li>
            </ol>
        </nav>

        <main class="glass-card doc-main">

            <section id="acceptance">
                <h2><span class="num">1</span> Acceptance of terms</h2>
                <p>
                    These Terms &amp; Conditions ("<strong>Terms</strong>") form a binding agreement between
                    you and AmaX Ltd. ("<strong>AmaX</strong>", "<strong>we</strong>", "<strong>us</strong>",
                    or "<strong>our</strong>") governing your access to and use of the AmaSports platform: a
                    mobile application for player profiles, live scoring and match statistics across
                    multiple sports, a companion website, and an administrative control panel
                    (collectively, the "<strong>Platform</strong>").
                </p>
                <p>
                    By creating an account, browsing the Platform, or making a payment, you confirm that you
                    have read, understood, and agree to be bound by these Terms and by our
                    <a href="{{ route('privacy-policy') }}">Privacy Policy</a>, which is incorporated into
                    these Terms by reference. If you do not agree, please do not use the Platform.
                </p>
            </section>

            <section id="who-we-are">
                <h2><span class="num">2</span> Who we are</h2>
                <p>
                    AmaSports is owned and operated by <strong>AmaX Ltd.</strong>, headquartered in Colombo,
                    Sri Lanka, with a regional office in Moradabad, India. Full contact details are listed
                    in <a href="#contact">Section 16</a> below.
                </p>
            </section>

            <section id="eligibility">
                <h2><span class="num">3</span> Eligibility &amp; accounts</h2>
                <ul>
                    <li>You must be at least 18 years old to register your own AmaSports account. Athletes under 18 may only be added to the Platform by a parent, guardian, coach, or academy administrator, who is responsible for that athlete's profile and data.</li>
                    <li>You must provide accurate, current information when registering (name, email, and password) and keep it up to date.</li>
                    <li>You are responsible for keeping your password confidential and for all activity that occurs under your account. Notify us immediately at <a href="mailto:alex@amaxlk.com">alex@amaxlk.com</a> if you suspect unauthorised access.</li>
                    <li>Accounts are personal to you and may not be sold, transferred, or shared, except where a coach or academy administrator manages profiles on behalf of the players in their care.</li>
                    <li>The Platform recognises three account roles — <strong>player/student</strong>, <strong>coach</strong>, and <strong>admin</strong> — each with different access to create, edit, or view match and profile data.</li>
                </ul>
            </section>

            <section id="the-service">
                <h2><span class="num">4</span> The service</h2>
                <p>AmaSports lets you:</p>
                <ul>
                    <li>Create and maintain player profiles with sport-specific statistics, achievements, team/college affiliations, and photos, across cricket and many other sports;</li>
                    <li>Follow live scores and match updates, synced in real time through our Firebase-backed live scoring service;</li>
                    <li>Watch selected match live streams, some of which require a one-time unlock purchase;</li>
                    <li>Search and view other players' public profiles and statistics;</li>
                    <li>Contact our support team and, for coaches and academy partners, manage matches, teams, and live scoring through the admin/control panel.</li>
                </ul>
                <div class="doc-callout">
                    Live scores and statistics are provided for informational and entertainment purposes
                    only. While we aim for accuracy, real-time data may occasionally lag, be corrected after
                    the fact, or differ from a match's official scorebook — AmaSports is not a substitute for
                    the official records kept by the relevant sporting body or organiser.
                </div>
            </section>

            <section id="payments">
                <h2><span class="num">5</span> Subscriptions, fees &amp; payments</h2>
                <h3>a. Player subscription</h3>
                <p>
                    Adding additional sports to a player profile, and editing sport profiles you've already
                    registered, requires an active annual subscription. The standard price is <strong>USD
                    10.00 per year</strong>; we may set a different amount for your country, shown to you
                    before checkout — see our <a href="{{ route('pricing') }}">Pricing</a> page. Some
                    players may be eligible for a free trial period, offered entirely at our discretion.
                </p>
                <h3>b. Live-stream unlock</h3>
                <p>
                    Certain match live streams require a one-time unlock fee of <strong>USD 5.00 per
                    match</strong> to view that match's stream. Live scoring itself always remains free.
                </p>
                <h3>c. How payment works</h3>
                <ul>
                    <li>All payments on the Platform are processed by <strong>PayHere (Pvt) Ltd</strong>, a licensed third-party payment service provider — AmaX never receives or stores your full card, bank, or wallet details. See our <a href="{{ route('privacy-policy') }}">Privacy Policy</a> for details.</li>
                    <li>Both the player subscription and the live-stream unlock are <strong>one-time, non-recurring purchases</strong> for a fixed period (one year, or a single match) — we do not automatically charge you again when that period ends. Access simply expires and you may purchase again to continue.</li>
                    <li>Prices are shown in the currency and amount applicable at checkout and may include applicable taxes. Prices may change from time to time; changes will not affect a subscription or unlock you have already paid for.</li>
                    <li>You are responsible for any fees your bank, card issuer, or PayHere itself may separately charge for the transaction.</li>
                </ul>
            </section>

            <section id="refunds">
                <h2><span class="num">6</span> Refunds &amp; cancellations</h2>
                <p>
                    Because subscriptions and live-stream unlocks grant immediate digital access, payments
                    are <strong>generally non-refundable</strong> once access has been activated, except in
                    the circumstances described in our dedicated <a href="{{ route('refund-policy') }}">Refund
                    Policy</a>. In short:
                </p>
                <ul>
                    <li>Eligible refunds include duplicate charges, a charge that was never activated on your account, or a live stream that failed due to a fault on our side;</li>
                    <li><strong>Any refund we approve is paid back to the original payment method you used at checkout</strong> — the same card, bank account, or e‑wallet processed by PayHere. We do not issue refunds by any other method.</li>
                    <li>Since neither product auto-renews, there is nothing to "cancel" going forward — simply choose not to purchase again when your access expires.</li>
                </ul>
                <p>See the full <a href="{{ route('refund-policy') }}">Refund Policy</a> for eligibility, timelines, and how to request one.</p>
            </section>

            <section id="conduct">
                <h2><span class="num">7</span> User content &amp; conduct</h2>
                <p>
                    "User content" means any profile information, photographs, statistics, or messages
                    (including Contact Us submissions) that you or a coach/academy submits to the Platform.
                    You are responsible for the accuracy and legality of the content you submit, and you
                    confirm you have the right to upload any photograph or logo you provide.
                </p>
                <p>While using the Platform, you agree not to:</p>
                <ul>
                    <li>Impersonate another person or misrepresent your affiliation with any team, school, or academy;</li>
                    <li>Submit false, defamatory, or intentionally misleading statistics or achievements;</li>
                    <li>Upload content that is unlawful, obscene, harassing, or infringes someone else's rights;</li>
                    <li>Attempt to interfere with, disrupt, or gain unauthorised access to the Platform, its live-scoring feed, or another user's account;</li>
                    <li>Use the Platform for any purpose that violates applicable law.</li>
                </ul>
                <p>We may remove content or suspend accounts that breach this section — see <a href="#termination">Section 13</a>.</p>
            </section>

            <section id="player-data">
                <h2><span class="num">8</span> Player profiles managed by coaches</h2>
                <p>
                    Where a coach or academy administrator creates or edits a player profile on behalf of an
                    athlete — including a minor — they confirm that they are authorised to do so (as the
                    athlete's coach, academy representative, parent, or guardian) and are responsible for the
                    accuracy of the data entered and for obtaining any consent required under applicable law.
                    A player or their guardian may contact us at any time to request a correction or removal
                    of a profile entered on their behalf.
                </p>
            </section>

            <section id="ip">
                <h2><span class="num">9</span> Intellectual property</h2>
                <p>
                    The Platform, including its design, branding, logos, "AmaSports" and "AmaX" names, and
                    underlying software, is owned by AmaX Ltd. and protected by applicable intellectual
                    property laws. Nothing in these Terms grants you any right to use our branding except as
                    needed to use the Platform as intended.
                </p>
                <p>
                    You retain ownership of the photographs and profile content you upload. By submitting
                    content, you grant AmaX a non-exclusive, worldwide, royalty-free licence to host, display,
                    and reproduce it within the Platform (for example, on a player's public profile or a
                    match scorecard) for as long as your account or that content remains on the Platform.
                </p>
            </section>

            <section id="third-party">
                <h2><span class="num">10</span> Live streams &amp; third-party services</h2>
                <p>
                    Match live streams may be embedded from third-party video platforms, and payments are
                    processed by PayHere. These third parties operate under their own terms and privacy
                    policies, and we are not responsible for their availability, content, or performance. We
                    do not guarantee that any given match will be streamed, or that a stream will be free of
                    interruptions, delays, or quality issues outside our control.
                </p>
            </section>

            <section id="disclaimer">
                <h2><span class="num">11</span> Disclaimers</h2>
                <p>
                    The Platform is provided "as is" and "as available," without warranties of any kind,
                    whether express or implied, including but not limited to warranties of merchantability,
                    fitness for a particular purpose, non-infringement, or uninterrupted, error-free
                    operation. We do not warrant that live scores, statistics, or achievements displayed on
                    the Platform are complete, accurate, or free of delay at every moment.
                </p>
            </section>

            <section id="liability">
                <h2><span class="num">12</span> Limitation of liability</h2>
                <p>
                    To the fullest extent permitted by applicable law, AmaX Ltd. shall not be liable for any
                    indirect, incidental, special, consequential, or punitive damages, or any loss of data,
                    profits, or goodwill, arising from your use of (or inability to use) the Platform. Where
                    liability cannot be excluded, our total liability to you for any claim relating to the
                    Platform or a payment made through it is limited to the amount you paid to us for the
                    subscription or live-stream unlock giving rise to the claim.
                </p>
                <div class="doc-callout warn">
                    Nothing in these Terms limits any liability that cannot be limited or excluded under the
                    law that applies to you, including liability for fraud or for death or personal injury
                    caused by our negligence.
                </div>
            </section>

            <section id="termination">
                <h2><span class="num">13</span> Suspension &amp; termination</h2>
                <ul>
                    <li>You may stop using the Platform and request deletion of your account at any time by contacting <a href="mailto:alex@amaxlk.com">alex@amaxlk.com</a>.</li>
                    <li>We may suspend or terminate your access to the Platform, with or without notice, if you breach these Terms, if we reasonably suspect fraud or abuse, or if required by law.</li>
                    <li>Termination does not entitle you to a refund for subscriptions or unlocks already used, except as described in our <a href="{{ route('refund-policy') }}">Refund Policy</a>.</li>
                </ul>
            </section>

            <section id="law">
                <h2><span class="num">14</span> Governing law &amp; disputes</h2>
                <p>
                    These Terms are governed by the laws of the Democratic Socialist Republic of Sri Lanka,
                    without regard to conflict-of-law principles. Any dispute arising out of or relating to
                    these Terms or the Platform shall be subject to the exclusive jurisdiction of the courts
                    of Colombo, Sri Lanka, except where mandatory consumer-protection law in your own country
                    gives you the right to bring a claim locally.
                </p>
            </section>

            <section id="changes">
                <h2><span class="num">15</span> Changes to these terms</h2>
                <p>
                    We may update these Terms from time to time to reflect changes in our Platform, pricing,
                    or legal requirements. We will update the "Effective date" above whenever we do, and
                    where a change is material we will take reasonable steps to notify you (for example,
                    in-app or by email) before it takes effect. Continuing to use the Platform after a change
                    takes effect means you accept the updated Terms.
                </p>
            </section>

            <section id="contact">
                <h2><span class="num">16</span> Contact us</h2>
                <p>If you have questions about these Terms, reach us at:</p>
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

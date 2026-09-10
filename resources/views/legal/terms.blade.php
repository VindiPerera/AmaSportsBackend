<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow">
    <title>Terms &amp; Conditions — AmaSports</title>
    <meta name="description" content="The terms that govern your use of the AmaSports app, website, and admin panel, including subscriptions, live-stream unlocks, and payments processed through PayHere.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand: #0366D6;
            --brand-dark: #024FAC;
            --ink: #1e293b;
            --ink-soft: #475569;
            --line: #e2e8f0;
            --paper: #ffffff;
            --wash: #f8fafc;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--ink);
            background: var(--wash);
            -webkit-font-smoothing: antialiased;
        }
        a { color: var(--brand); }

        header.top {
            background: var(--brand);
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 10;
            box-shadow: 0 1px 2px rgba(0,0,0,.08);
        }
        .top-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .top-inner .mark {
            width: 30px; height: 30px; border-radius: 8px;
            background: #fff; color: var(--brand);
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 14px;
        }
        .top-inner .brand { font-weight: 800; font-size: 18px; letter-spacing: -0.02em; }
        .top-inner .tag { margin-left: auto; font-size: 12px; font-weight: 600; color: #d6e8ff; }

        .hero {
            background: linear-gradient(180deg, #eaf3ff 0%, var(--wash) 100%);
            border-bottom: 1px solid var(--line);
        }
        .hero-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 20px 28px;
        }
        .eyebrow {
            display: inline-block;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--brand-dark);
            background: #dceaff;
            padding: 4px 10px;
            border-radius: 999px;
            margin-bottom: 12px;
        }
        .hero h1 {
            margin: 0 0 8px;
            font-size: clamp(28px, 4vw, 40px);
            font-weight: 900;
            letter-spacing: -0.02em;
        }
        .hero p.lead {
            margin: 0;
            color: var(--ink-soft);
            font-size: 15px;
            max-width: 65ch;
            line-height: 1.6;
        }
        .meta-row {
            margin-top: 18px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            font-size: 12px;
            color: var(--ink-soft);
        }
        .meta-pill {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 6px 10px;
            font-weight: 600;
        }

        .layout {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 20px 80px;
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 32px;
            align-items: start;
        }
        @media (max-width: 820px) {
            .layout { grid-template-columns: 1fr; }
        }

        nav.toc {
            position: sticky;
            top: 74px;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 16px;
        }
        nav.toc h2 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #94a3b8;
            margin: 0 0 10px;
            font-weight: 800;
        }
        nav.toc ol {
            list-style: none;
            margin: 0; padding: 0;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        nav.toc a {
            display: block;
            text-decoration: none;
            color: var(--ink-soft);
            font-size: 13px;
            font-weight: 600;
            padding: 7px 8px;
            border-radius: 8px;
        }
        nav.toc a:hover { background: var(--wash); color: var(--brand); }

        main.doc {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 8px 32px 32px;
        }
        section { padding-top: 28px; border-top: 1px solid var(--line); margin-top: 28px; }
        section:first-of-type { border-top: none; margin-top: 8px; }
        section h2 {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            gap: 10px;
            scroll-margin-top: 80px;
        }
        section h2 .num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px; height: 26px;
            border-radius: 8px;
            background: #eaf3ff;
            color: var(--brand-dark);
            font-size: 12px;
            font-weight: 900;
            flex: none;
        }
        section h3 {
            font-size: 14px;
            font-weight: 800;
            margin: 18px 0 8px;
            color: var(--ink);
        }
        section p, section li {
            font-size: 14.5px;
            line-height: 1.75;
            color: var(--ink-soft);
        }
        section ul, section ol.plain { padding-left: 20px; margin: 10px 0; }
        section li { margin-bottom: 6px; }
        strong { color: var(--ink); }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 14px 0;
            font-size: 13.5px;
        }
        table.data-table th, table.data-table td {
            text-align: left;
            padding: 10px 12px;
            border: 1px solid var(--line);
            vertical-align: top;
            line-height: 1.6;
            color: var(--ink-soft);
        }
        table.data-table th {
            background: var(--wash);
            color: var(--ink);
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .callout {
            background: #eaf3ff;
            border: 1px solid #cfe3ff;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 13.5px;
            color: #0a3d78;
            line-height: 1.7;
            margin: 14px 0;
        }
        .callout strong { color: #0a3d78; }
        .callout.warn {
            background: #fff7ed;
            border-color: #fed7aa;
            color: #9a3412;
        }
        .callout.warn strong { color: #9a3412; }

        .offices {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-top: 14px;
        }
        @media (max-width: 620px) { .offices { grid-template-columns: 1fr; } }
        .office-card {
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 14px 16px;
            background: var(--wash);
        }
        .office-card .flag { font-size: 13px; font-weight: 800; color: var(--brand-dark); margin-bottom: 6px; }
        .office-card p { margin: 2px 0; font-size: 13.5px; }

        footer.site {
            border-top: 1px solid var(--line);
            background: #fff;
            padding: 18px 20px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }
        footer.site a { color: #64748b; font-weight: 700; }
    </style>
</head>
<body>

    <header class="top">
        <div class="top-inner">
            <span class="mark">A</span>
            <span class="brand">AmaSports</span>
            <span class="tag">Operated by AmaX Ltd.</span>
        </div>
    </header>

    <div class="hero">
        <div class="hero-inner">
            <span class="eyebrow">Legal</span>
            <h1>Terms &amp; Conditions</h1>
            <p class="lead">
                These Terms &amp; Conditions govern your use of the AmaSports mobile app, website, and
                admin/control panel (together, the "Platform"), and your purchase of any subscription or
                live-stream unlock through our payment partner <strong>PayHere</strong>. Please read them
                carefully before creating an account or making a payment.
            </p>
            <div class="meta-row">
                <span class="meta-pill">Effective date: 10 September 2026</span>
                <span class="meta-pill">Applies to: AmaSports app, amasports.app, admin panel</span>
                <span class="meta-pill">Contact: alex@amaxlk.com</span>
            </div>
        </div>
    </div>

    <div class="layout">
        <nav class="toc">
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

        <main class="doc">

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
                <div class="callout">
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
                    before checkout. Some players may be eligible for a free trial period, offered entirely
                    at our discretion.
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
                    are <strong>generally non-refundable</strong> once access has been activated, except:
                </p>
                <ul>
                    <li>Where you were charged in error or charged twice for the same subscription or match;</li>
                    <li>Where a live stream you paid to unlock failed to become available due to a fault on our side;</li>
                    <li>Where a refund is required by the consumer-protection law that applies to you.</li>
                </ul>
                <p>
                    To request a refund, contact <a href="mailto:alex@amaxlk.com">alex@amaxlk.com</a>
                    with your account email and payment reference within 7 days of the charge. Approved
                    refunds are returned to your original PayHere payment method and may take a few business
                    days to appear, depending on your bank.
                </p>
                <p>
                    Since neither the subscription nor the live-stream unlock auto-renews, there is nothing
                    to "cancel" going forward — simply choose not to purchase again when your access expires.
                </p>
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
                <div class="callout warn">
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
                    <li>Termination does not entitle you to a refund for subscriptions or unlocks already used, except as described in <a href="#refunds">Section 6</a>.</li>
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
                <div class="offices">
                    <div class="office-card">
                        <div class="flag">🇱🇰 Head Office — Colombo</div>
                        <p><strong>AmaX Headquarters</strong></p>
                        <p>AmaX, Sutton Indoor Cricket Center</p>
                        <p>29 Maitland Place, Colombo 07, Sri Lanka</p>
                        <p>Phone: +94 75 220 6006</p>
                        <p>Email: <a href="mailto:alex@amaxlk.com">alex@amaxlk.com</a></p>
                    </div>
                    <div class="office-card">
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

    <footer class="site">
        &copy; {{ date('Y') }} AmaX Ltd. All rights reserved. &nbsp;·&nbsp;
        <a href="{{ route('privacy-policy') }}">Privacy Policy</a> &nbsp;·&nbsp;
        <a href="{{ url('/') }}">AmaSports</a>
    </footer>

</body>
</html>

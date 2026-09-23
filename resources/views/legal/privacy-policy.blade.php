@extends('public.layouts.app')

@section('title', 'Privacy Policy')
@section('meta_description', 'How AmaX Ltd. collects, uses, and protects personal data across the AmaSports app, website, and admin panel, including payments made via PayHere.')

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
    .doc-callout.warn { background: #fefce8; border-color: #fef08a; color: #854d0e; }
    .doc-callout.warn strong { color: #713f12; }
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
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-3">Privacy Policy</h1>
        <p class="text-sm sm:text-base text-slate-600 font-medium max-w-3xl leading-relaxed">
            This Privacy Policy explains what personal data AmaX Ltd. collects across the AmaSports mobile
            app, website, and admin/control panel (together, the "Platform"), why we collect it, how it is
            protected, and the choices available to you — including when you pay for a subscription or
            live-stream unlock through <strong class="text-slate-800">PayHere</strong>.
        </p>
        <div class="meta-row">
            <span class="meta-pill">Effective date: 10 September 2026</span>
            <span class="meta-pill">Applies to: AmaSports app, website, admin panel</span>
            <span class="meta-pill">Contact: alex@amaxlk.com</span>
        </div>
    </div>
</section>

<section class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
    <div class="doc-layout">
        <nav class="glass-card doc-toc">
            <h2>On this page</h2>
            <ol>
                <li><a href="#introduction">1. Introduction</a></li>
                <li><a href="#who-we-are">2. Who we are</a></li>
                <li><a href="#information-we-collect">3. Information we collect</a></li>
                <li><a href="#how-we-use">4. How we use it</a></li>
                <li><a href="#payments">5. Payments (PayHere)</a></li>
                <li><a href="#cookies">6. Cookies &amp; local storage</a></li>
                <li><a href="#sharing">7. Sharing your information</a></li>
                <li><a href="#children">8. Youth athletes &amp; children</a></li>
                <li><a href="#retention">9. Data retention</a></li>
                <li><a href="#security">10. Security</a></li>
                <li><a href="#rights">11. Your rights &amp; choices</a></li>
                <li><a href="#transfers">12. International transfers</a></li>
                <li><a href="#third-party-links">13. Third-party links</a></li>
                <li><a href="#changes">14. Changes to this policy</a></li>
                <li><a href="#contact">15. Contact us</a></li>
            </ol>
        </nav>

        <main class="glass-card doc-main">

            <section id="introduction">
                <h2><span class="num">1</span> Introduction</h2>
                <p>
                    AmaX Ltd. ("<strong>AmaX</strong>", "<strong>we</strong>",
                    "<strong>us</strong>", or "<strong>our</strong>") builds and operates the AmaSports
                    platform: a mobile application for player profiles, live scoring and match statistics
                    across multiple sports, a companion website, and an administrative control panel used
                    by our staff and academy partners to manage matches, teams and live scores
                    (collectively, the "<strong>Platform</strong>").
                </p>
                <p>
                    This Policy describes what personal data we process when you create an account, browse
                    the Platform, submit an enquiry, or make a payment — for example an annual player
                    subscription or a pay-per-match live-stream unlock — through our payment partner
                    <strong>PayHere</strong>. By using the Platform or completing a
                    payment, you agree to the collection and use of information as described here.
                </p>
            </section>

            <section id="who-we-are">
                <h2><span class="num">2</span> Who we are</h2>
                <p>
                    AmaSports is owned and operated by <strong>AmaX Ltd.</strong>, headquartered in Colombo,
                    Sri Lanka, with a regional office in Moradabad, India. Full contact details are listed in
                    <a href="#contact">Section 15</a> below. AmaX Ltd. is the "data controller" responsible
                    for the personal data described in this Policy.
                </p>
            </section>

            <section id="information-we-collect">
                <h2><span class="num">3</span> Information we collect</h2>
                <p>We collect the following categories of information:</p>

                <h3>a. Account information</h3>
                <p>
                    Full name, email address, password (stored as a salted, irreversible hash — we cannot
                    see or recover your plain-text password), account role (player/student, coach, or
                    admin), and profile photo or avatar.
                </p>

                <h3>b. Player &amp; sports profile data</h3>
                <p>
                    Sport(s) played, team/school/college affiliation and logos, age category, match and
                    career statistics relevant to the sport (for example batting/bowling figures, goals,
                    times, distances, or bout records), achievement badges, player photographs, and match
                    participation history. Coaches, academies, and administrators may enter or manage this
                    data on behalf of the athletes they supervise.
                </p>

                <h3>c. Identity verification data</h3>
                <p>
                    One-time passcodes (OTP) sent to your email or phone number to confirm you own the
                    account. OTPs are short-lived and are not retained once verification is complete.
                </p>

                <h3>d. Payment &amp; subscription data</h3>
                <p>
                    When you purchase a player subscription or unlock a paid live stream, we record the
                    amount charged, currency, your billing country, the plan or match purchased, a
                    transaction/order reference, and the payment status and timestamp returned to us by our
                    payment processor. <strong>We do not collect, transmit, or store your full card number,
                    CVV, or online banking credentials</strong> — those are entered directly on PayHere's own
                    secure, PCI‑DSS‑compliant payment pages. See <a href="#payments">Section 5</a>
                    for details.
                </p>

                <h3>e. Support &amp; contact-form data</h3>
                <p>
                    Your name, email address, chosen enquiry topic, and the content of any message you send
                    us through the Contact Us screen.
                </p>

                <h3>f. Technical &amp; usage data</h3>
                <p>
                    IP address, browser or device user-agent, session identifiers, sign-in timestamps, and
                    general diagnostic/log data we need to operate the Platform securely and reliably.
                </p>

                <h3>g. Live match data</h3>
                <p>
                    Ball-by-ball and match score updates are synced in real time through our Firebase-backed
                    live scoring service. This is public sporting information (scores, overs, wickets, sets,
                    etc.) and is not linked to your personal account.
                </p>
            </section>

            <section id="how-we-use">
                <h2><span class="num">4</span> How we use your information</h2>
                <ul>
                    <li>Create and manage your account and player, coach, or admin profile;</li>
                    <li>Display match schedules, live scores, statistics, and achievements within the Platform;</li>
                    <li>Process subscription and live-stream-unlock payments and maintain your purchase/order history;</li>
                    <li>Send essential account, OTP-verification, and transactional emails or notifications;</li>
                    <li>Respond to enquiries submitted through Contact Us;</li>
                    <li>Detect, prevent, and investigate fraud, abuse, and security incidents;</li>
                    <li>Maintain, troubleshoot, and improve the Platform's features and performance; and</li>
                    <li>Comply with legal, tax, and regulatory obligations, including those required by our payment partners.</li>
                </ul>
                <div class="doc-callout">We do not sell your personal information to anyone.</div>
            </section>

            <section id="payments">
                <h2><span class="num">5</span> Payments — PayHere</h2>
                <p>
                    AmaSports charges for annual player subscriptions and single-match live-stream unlocks.
                    All card, bank, and wallet payments are handled by our payment processor,
                    <strong>PayHere (Pvt) Ltd</strong>, a licensed payment service provider regulated by the
                    Central Bank of Sri Lanka — not directly by AmaX.
                </p>
                <ul>
                    <li>When you choose to pay, you are redirected to a secure, hosted checkout page operated by PayHere.</li>
                    <li>You enter your card, bank, or wallet details directly on PayHere's page; AmaX's servers never receive or store your full card number, expiry date, CVV, or banking credentials.</li>
                    <li>Once payment succeeds, PayHere notifies AmaSports of the result (success, failure, or cancellation) together with a transaction reference, the amount, and the currency, so we can activate your subscription or stream access.</li>
                    <li>PayHere processes this data under its own privacy policy and is independently responsible for the security of the payment details you provide to it: <a href="https://www.payhere.lk/privacy-policy" target="_blank" rel="noopener noreferrer">PayHere Privacy Policy</a>.</li>
                    <li>We retain a record of your transactions (amount, date, plan, and status) for accounting, support, and legal/tax purposes even after the payment itself is complete.</li>
                    <li>Refunds, when approved, are always paid back to the original payment method used at checkout — see our <a href="{{ route('refund-policy') }}">Refund Policy</a>.</li>
                </ul>
            </section>

            <section id="cookies">
                <h2><span class="num">6</span> Cookies &amp; local storage</h2>
                <p>
                    Our admin panel and website use standard session cookies to keep administrators signed
                    in and to protect forms against cross-site request forgery. The AmaSports mobile app
                    uses on-device secure storage (not browser cookies) to keep you signed in between
                    sessions. We do not use third-party advertising cookies or trackers.
                </p>
            </section>

            <section id="sharing">
                <h2><span class="num">7</span> Sharing your information</h2>
                <p>We share personal data only with the following categories of recipients, and only as needed to run the Platform:</p>
                <table class="doc-table">
                    <thead>
                        <tr><th>Recipient</th><th>Purpose</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>PayHere</td><td>Processing subscription and live-stream payments</td></tr>
                        <tr><td>Google Firebase</td><td>Real-time delivery of live match scores to the app</td></tr>
                        <tr><td>Transactional email providers (e.g. Postmark, Resend, Amazon SES)</td><td>Delivering account, OTP, and receipt emails</td></tr>
                        <tr><td>Cloud hosting infrastructure</td><td>Running and securing our servers, database, and backups</td></tr>
                        <tr><td>Coaches, team &amp; academy administrators</td><td>Managing the profiles and statistics of the athletes they supervise</td></tr>
                        <tr><td>Law enforcement or regulators</td><td>Only where required by applicable law or a valid legal request</td></tr>
                    </tbody>
                </table>
                <p>Every third party we work with is contractually required to protect your data and to use it only for the purpose we've engaged them for.</p>
            </section>

            <section id="children">
                <h2><span class="num">8</span> Youth athletes &amp; children</h2>
                <p>
                    Many athletes featured on AmaSports are students and youth players. AmaSports accounts
                    (login credentials) are intended for users aged 18 or over, or for coaches, parents,
                    guardians, or academy staff who create and manage player profiles on behalf of a minor
                    athlete. We do not knowingly collect personal information directly from a child under 13
                    (or the minimum age of digital consent in their jurisdiction) without the involvement of
                    a parent, guardian, or supervising coach/academy.
                </p>
                <p>
                    If you believe a minor has provided us personal data directly and without appropriate
                    consent, please contact us at <a href="mailto:alex@amaxlk.com">alex@amaxlk.com</a>
                    and we will review and remove it as appropriate.
                </p>
            </section>

            <section id="retention">
                <h2><span class="num">9</span> Data retention</h2>
                <p>
                    We keep account and profile data for as long as your account remains active, plus a
                    reasonable period afterwards to allow reactivation and to meet legal, tax, and dispute
                    obligations. Transaction records are retained for as long as required by applicable
                    accounting and tax law. OTP codes are deleted shortly after they expire or are used.
                    You may request earlier deletion — see <a href="#rights">Section 11</a>.
                </p>
            </section>

            <section id="security">
                <h2><span class="num">10</span> Security</h2>
                <p>
                    We use industry-standard safeguards to protect your data, including encrypted
                    connections (HTTPS/TLS) between the app, website, and our servers, salted password
                    hashing, access-controlled admin accounts, and reliance on our PCI‑DSS‑compliant processor
                    (PayHere) for all payment card handling. No method of transmission or storage is
                    100% secure, but we work continuously to protect your information against unauthorized
                    access, alteration, disclosure, or destruction.
                </p>
            </section>

            <section id="rights">
                <h2><span class="num">11</span> Your rights &amp; choices</h2>
                <ul>
                    <li>Access and update most of your profile information directly within the app;</li>
                    <li>Request a copy of the personal data we hold about you;</li>
                    <li>Request correction or deletion of your account and associated data;</li>
                    <li>Opt out of non-essential communications from us at any time;</li>
                    <li>Withdraw consent previously given, where processing relies on consent.</li>
                </ul>
                <p>
                    To exercise any of these rights, contact <a href="mailto:alex@amaxlk.com">alex@amaxlk.com</a>.
                    Depending on where you live — for example under the Sri Lanka Personal Data Protection
                    Act, No. 9 of 2022, the EU/UK GDPR, or other local law — you may have additional rights;
                    we will honour requests to the extent required by the law that applies to you.
                </p>
            </section>

            <section id="transfers">
                <h2><span class="num">12</span> International transfers</h2>
                <p>
                    AmaX operates from Sri Lanka and India and relies on global infrastructure and payment
                    providers. As a result, your data may be processed or stored in a country other than
                    where you live. Wherever this happens, we require the recipient to protect your data to
                    a standard consistent with this Policy.
                </p>
            </section>

            <section id="third-party-links">
                <h2><span class="num">13</span> Third-party links</h2>
                <p>
                    The Platform may link out to third-party sites or services — including PayHere
                    and embedded live-stream video — that are governed by their own privacy policies. We
                    encourage you to review those policies before sharing information with them.
                </p>
            </section>

            <section id="changes">
                <h2><span class="num">14</span> Changes to this policy</h2>
                <p>
                    We may update this Privacy Policy from time to time to reflect changes in our practices,
                    features, or legal requirements. We will update the "Effective date" above whenever we
                    do, and where a change is material we will take reasonable steps to notify you (for
                    example, in-app or by email) before it takes effect.
                </p>
            </section>

            <section id="contact">
                <h2><span class="num">15</span> Contact us</h2>
                <p>If you have questions about this Privacy Policy or how your data is handled, reach us at:</p>
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

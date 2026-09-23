<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #F8F9FB;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #0F172A;
            padding: 24px;
            box-sizing: border-box;
        }
        .card {
            background: #FFFFFF;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 24px;
            padding: 44px 32px;
            max-width: 420px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 50px -10px rgba(15, 23, 42, 0.08);
        }
        .icon {
            width: 64px;
            height: 64px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px;
            font-weight: 900;
        }
        .icon.success { background: #DCFCE7; color: #16A34A; }
        .icon.failure { background: #FEE2E2; color: #DC2626; }
        h1 { font-size: 20px; font-weight: 900; margin: 0 0 10px; letter-spacing: -0.02em; }
        p { font-size: 14px; line-height: 1.6; color: #64748B; margin: 0 0 24px; font-weight: 500; }
        .return-link {
            display: inline-block;
            background: #EC1F24;
            color: #FFFFFF;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 14px;
            box-shadow: 0 8px 20px -4px rgba(236, 31, 36, 0.35);
            transition: all 0.2s ease;
        }
        .return-link:hover {
            background: #B91C1C;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon {{ $success ? 'success' : 'failure' }}">{{ $success ? '✓' : '!' }}</div>
        <h1>{{ $title }}</h1>
        <p>{{ $message }}</p>
        <a class="return-link" href="{{ config('subscription.mobile_return_scheme') }}">Return to app</a>
    </div>
    <script>
        // Hit from the app's in-app browser sheet (expo-web-browser's
        // openAuthSessionAsync) — bouncing straight to the app's deep link
        // scheme is what lets that API detect the redirect and auto-close
        // the sheet, instead of requiring the player to dismiss it by hand.
        // Capture itself already happened server-side above this point
        // (return()/cancel() in SubscriptionPaymentController /
        // StreamAccessPaymentController, whichever rendered this view), so
        // this redirect carries no payment-relevant data — it's purely a
        // "hand control back to the app" signal. Read directly from config
        // here rather than threaded through every controller call site
        // that renders this shared view.
        window.location.href = "{{ config('subscription.mobile_return_scheme') }}";
    </script>
</body>
</html>

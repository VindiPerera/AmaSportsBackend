<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redirecting to PayHere — {{ config('app.name') }}</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #F8F9FB;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #0F172A;
            padding: 24px;
            box-sizing: border-box;
            text-align: center;
        }
        .spinner {
            width: 40px;
            height: 40px;
            margin: 0 auto 20px;
            border: 4px solid #E2E8F0;
            border-top-color: #EC1F24;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        p { font-size: 14px; color: #64748B; margin: 0 0 20px; }
        button {
            background: #EC1F24;
            color: #FFFFFF;
            border: 0;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 12px 28px;
            border-radius: 14px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <form id="payhere-checkout" method="post" action="{{ $action }}">
        <div class="spinner"></div>
        <p>Taking you to PayHere secure checkout…</p>
        @foreach ($fields as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach
        <noscript><button type="submit">Continue to PayHere</button></noscript>
    </form>
    <script>
        document.getElementById('payhere-checkout').submit();
    </script>
</body>
</html>

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
            background: #E2ECFF;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #0F172A;
            padding: 24px;
            box-sizing: border-box;
        }
        .card {
            background: #FFFFFF;
            border-radius: 24px;
            padding: 40px 32px;
            max-width: 420px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 40px rgba(11, 31, 58, 0.12);
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
        }
        .icon.success { background: #DCFCE7; color: #22C55E; }
        .icon.failure { background: #FEE2E2; color: #EF4444; }
        h1 { font-size: 20px; font-weight: 800; margin: 0 0 12px; }
        p { font-size: 14px; line-height: 1.6; color: #64748B; margin: 0; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon {{ $success ? 'success' : 'failure' }}">{{ $success ? '✓' : '!' }}</div>
        <h1>{{ $title }}</h1>
        <p>{{ $message }}</p>
    </div>
</body>
</html>

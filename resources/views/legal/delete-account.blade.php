@extends('public.layouts.app')
@section('title', 'Delete Your Account')
@section('meta_description', 'Permanently delete your AmaX player account and all associated data.')
@section('content')
<style>
    .del-card { max-width: 40rem; margin: 0 auto; padding: 2rem; background: #ffffff; border: 1px solid rgba(226, 232, 240, 0.8); border-radius: 1.25rem; box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05); }
    .del-card h2 { font-size: 1.125rem; font-weight: 900; color: #0f172a; margin: 1.5rem 0 0.625rem; }
    .del-card h2:first-child { margin-top: 0; }
    .del-card p, .del-card li { font-size: 0.90625rem; line-height: 1.75; color: #475569; }
    .del-card ul { padding-left: 1.25rem; margin: 0.5rem 0; list-style: disc; }
    .del-card a { color: #EC1F24; font-weight: 700; text-decoration: underline; }
    .del-field { display: block; margin-top: 1rem; }
    .del-field span { display: block; font-size: 0.8125rem; font-weight: 700; color: #0f172a; margin-bottom: 0.375rem; }
    .del-field input[type=email], .del-field input[type=password] { width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 0.75rem; font-size: 0.9375rem; }
    .del-check { display: flex; gap: 0.625rem; align-items: flex-start; margin-top: 1rem; font-size: 0.875rem; color: #475569; }
    .del-check input { margin-top: 0.3rem; }
    .del-btn { margin-top: 1.5rem; width: 100%; padding: 0.875rem; border: none; border-radius: 0.75rem; background: #EC1F24; color: #fff; font-weight: 800; font-size: 0.9375rem; cursor: pointer; }
    .del-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; border-radius: 0.75rem; padding: 0.75rem 1rem; font-size: 0.875rem; margin-top: 1rem; }
    .del-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; border-radius: 0.75rem; padding: 1rem 1.25rem; font-size: 0.9375rem; }
</style>

<section class="relative overflow-hidden py-14 sm:py-16 bg-gradient-to-b from-slate-50 via-white to-[#F8F9FB] border-b border-slate-200/70">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 border border-red-200 text-brand-red text-xs font-black uppercase tracking-wider mb-3">
            Account
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-3">Delete your AmaX account</h1>
        <p class="text-sm sm:text-base text-slate-600 font-medium max-w-3xl leading-relaxed">
            You can delete your account from inside the app (Profile → Delete account) or right here, without the app.
        </p>
    </div>
</section>

<section class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
    <div class="del-card">
        @if (session('deleted'))
            <div class="del-success">
                <strong>Your account has been deleted.</strong> All of your profile data has been permanently removed.
            </div>
        @else
            <h2>What gets deleted</h2>
            <ul>
                <li>Your login details (name, email, phone number, password)</li>
                <li>Your player profile, every sport profile, stats, matches and achievements</li>
                <li>Your profile, cover and gallery photos, team and college logos, and score sheets</li>
                <li>Your subscription and free-trial status</li>
            </ul>
            <p>
                Deletion is immediate and permanent. It cannot be undone, and any remaining subscription time is
                forfeited. Payment records held by our payment partner PayHere may be kept by them as required by
                accounting and tax law.
            </p>

            <h2>Delete now</h2>
            <form method="POST" action="{{ route('delete-account.destroy') }}">
                @csrf
                @if ($errors->any())
                    <div class="del-error">{{ $errors->first() }}</div>
                @endif
                <label class="del-field">
                    <span>Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                </label>
                <label class="del-field">
                    <span>Password</span>
                    <input type="password" name="password" required autocomplete="current-password">
                </label>
                <label class="del-check">
                    <input type="checkbox" name="confirm" value="1" required>
                    I understand my account and all its data will be permanently deleted.
                </label>
                <button type="submit" class="del-btn">Permanently delete my account</button>
            </form>

            <h2>Forgot your password?</h2>
            <p>
                Reset it from the app's login screen, or email <a href="mailto:alex@amaxlk.com">alex@amaxlk.com</a>
                from the address on your account and we'll delete it within 30 days.
            </p>
        @endif
    </div>
</section>
@endsection

@extends('public.layouts.app')

@section('title', 'Log In — AmaX')
@section('meta_description', 'Log in to your AmaX user account to manage matches, schedule fixtures, and access live scores.')

@section('content')

<section style="max-width: 440px; margin: 4rem auto; padding: 0 1.5rem;">
    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.5rem; padding: 2.5rem 2rem; text-align: center;">

        {{-- Logo --}}
        <div style="margin-bottom: 1.5rem;">
            <img src="{{ asset('images/logo.png') }}" alt="AmaX" style="height: 3rem; width: auto; margin: 0 auto; object-fit: contain;" />
        </div>

        <h1 style="font-weight: 800; font-size: 1.5rem; color: #fff; margin-bottom: 0.5rem;">Welcome Back</h1>
        <p style="font-size: 0.875rem; color: rgba(148,163,184,0.75); margin-bottom: 2rem;">Log in to access your matches and schedule management</p>

        @if ($errors->any())
            <div class="flash-error" style="text-align: left; margin-bottom: 1.5rem;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" style="display: flex; flex-direction: column; gap: 1.25rem; text-align: left;">
            @csrf

            <div>
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: rgba(203,213,225,0.85); margin-bottom: 0.5rem;" for="email">
                    Email Address
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com" class="form-input">
            </div>

            <div>
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: rgba(203,213,225,0.85); margin-bottom: 0.5rem;" for="password">
                    Password
                </label>
                <input id="password" type="password" name="password" required placeholder="••••••••" class="form-input">
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; color: rgba(148,163,184,0.8); cursor: pointer;">
                    <input type="checkbox" name="remember" style="accent-color: #f59e0b; width: 1rem; height: 1rem; border-radius: 0.25rem;">
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn-primary" style="padding: 0.75rem; font-size: 0.9375rem; justify-content: center; width: 100%; margin-top: 0.5rem;">
                Log In
            </button>
        </form>

        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.06); font-size: 0.8125rem; color: rgba(148,163,184,0.7);">
            Use the same account on web and mobile.
        </div>
    </div>
</section>

@endsection

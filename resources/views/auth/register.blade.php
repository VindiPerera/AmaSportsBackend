@extends('public.layouts.app')

@section('title', 'Create Account — AmaX')
@section('meta_description', 'Create a free AmaX user account to manage matches, schedule fixtures, and access live scores.')

@section('content')

<section style="max-width: 480px; margin: 3rem auto; padding: 0 1.5rem;">
    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.5rem; padding: 2.5rem 2rem; text-align: center;">

        {{-- Logo --}}
        <div style="margin-bottom: 1.5rem;">
            <img src="{{ asset('images/logo.png') }}" alt="AmaX" style="height: 3rem; width: auto; margin: 0 auto; object-fit: contain;" />
        </div>

        <h1 style="font-weight: 800; font-size: 1.5rem; color: #fff; margin-bottom: 0.5rem;">Create Your Account</h1>
        <p style="font-size: 0.875rem; color: rgba(148,163,184,0.75); margin-bottom: 2rem;">Sign up once to access your account across web and mobile</p>

        @if ($errors->any())
            <div class="flash-error" style="text-align: left; margin-bottom: 1.5rem;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" style="display: flex; flex-direction: column; gap: 1.25rem; text-align: left;">
            @csrf

            <div>
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: rgba(203,213,225,0.85); margin-bottom: 0.5rem;" for="name">
                    Full Name *
                </label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="John Smith" class="form-input">
            </div>

            <div>
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: rgba(203,213,225,0.85); margin-bottom: 0.5rem;" for="email">
                    Email Address *
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com" class="form-input">
            </div>

            <div>
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: rgba(203,213,225,0.85); margin-bottom: 0.5rem;" for="phone">
                    Phone Number (Optional)
                </label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="+94 77 123 4567" class="form-input">
            </div>

            <div>
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: rgba(203,213,225,0.85); margin-bottom: 0.5rem;" for="password">
                    Password *
                </label>
                <input id="password" type="password" name="password" required placeholder="••••••••" class="form-input">
            </div>

            <div>
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: rgba(203,213,225,0.85); margin-bottom: 0.5rem;" for="password_confirmation">
                    Confirm Password *
                </label>
                <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="••••••••" class="form-input">
            </div>

            <button type="submit" class="btn-primary" style="padding: 0.75rem; font-size: 0.9375rem; justify-content: center; width: 100%; margin-top: 0.5rem;">
                Create Free Account
            </button>
        </form>

        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.06); font-size: 0.8125rem; color: rgba(148,163,184,0.75);">
            Already have an account? <a href="{{ route('login') }}" style="color: #f59e0b; font-weight: 700; text-decoration: none;">Log In</a>
        </div>
    </div>
</section>

@endsection

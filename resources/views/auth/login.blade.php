@extends('public.layouts.app')

@section('title', 'Sign In — AmaX')
@section('meta_description', 'Sign in to your AmaX account to manage matches, fixtures, and sports analytics.')

@section('content')

<section class="max-w-md mx-auto px-4 sm:px-6 py-12 sm:py-16">
    <x-card class="p-8 sm:p-10 shadow-lg">
        {{-- Header --}}
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="AmaX" class="h-10 w-auto mx-auto object-contain mb-4" />
            <h1 class="text-2xl font-black text-brand-charcoal tracking-tight">Welcome Back</h1>
            <p class="text-xs text-slate-500 font-semibold mt-1">Sign in to manage your matches and athletic profile</p>
        </div>

        @if ($errors->any())
            <x-alert type="error" title="Sign In Failed">
                <ul class="list-disc pl-5 space-y-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
            @csrf

            <x-input
                type="email"
                name="email"
                label="Email Address"
                required
                autofocus
                placeholder="athlete@example.com"
            />

            <x-input
                type="password"
                name="password"
                label="Password"
                required
                placeholder="••••••••"
            />

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 text-slate-600 font-semibold cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-red focus:ring-brand-red/30">
                    <span>Remember me</span>
                </label>
            </div>

            <div class="pt-2">
                <x-button type="submit" variant="primary" size="lg" class="w-full justify-center">
                    Log In
                </x-button>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center text-xs text-slate-500 font-medium">
            <span>Don't have an account yet?</span>
            <a href="{{ route('register') }}" class="font-extrabold text-brand-red hover:underline ml-1">Create Account</a>
        </div>
    </x-card>
</section>

@endsection

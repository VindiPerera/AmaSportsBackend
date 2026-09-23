@extends('public.layouts.app')

@section('title', 'Create Free Account — AmaX')
@section('meta_description', 'Create a free AmaX account to track matches, manage rosters, and build your digital athletic profile.')

@section('content')

<section class="max-w-lg mx-auto px-4 sm:px-6 py-12 sm:py-16">
    <x-card class="p-8 sm:p-10 shadow-lg">
        {{-- Header --}}
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="AmaX" class="h-10 w-auto mx-auto object-contain mb-4" />
            <h1 class="text-2xl font-black text-brand-charcoal tracking-tight">Create Your Account</h1>
            <p class="text-xs text-slate-500 font-semibold mt-1">One clear sports profile across web and mobile</p>
        </div>

        @if ($errors->any())
            <x-alert type="error" title="Registration Incomplete">
                <ul class="list-disc pl-5 space-y-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
            @csrf

            <x-input
                name="name"
                label="Full Name"
                required
                autofocus
                placeholder="e.g. John Smith"
            />

            <x-input
                type="email"
                name="email"
                label="Email Address"
                required
                placeholder="athlete@example.com"
            />

            <x-input
                type="text"
                name="phone"
                label="Phone Number (Optional)"
                placeholder="+94 77 123 4567"
            />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-input
                    type="password"
                    name="password"
                    label="Password"
                    required
                    placeholder="••••••••"
                />

                <x-input
                    type="password"
                    name="password_confirmation"
                    label="Confirm Password"
                    required
                    placeholder="••••••••"
                />
            </div>

            <div class="pt-2">
                <x-button type="submit" variant="primary" size="lg" class="w-full justify-center">
                    Create Free Account
                </x-button>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center text-xs text-slate-500 font-medium">
            <span>Already have an account?</span>
            <a href="{{ route('login') }}" class="font-extrabold text-brand-red hover:underline ml-1">Sign In</a>
        </div>
    </x-card>
</section>

@endsection

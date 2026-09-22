@extends('public.layouts.app')

@section('title', 'About AmaX — The Digital Sports Engine')
@section('meta_description', 'Learn about AmaX — the digital sports platform built to empower athletes, coaches, and clubs across web and mobile.')

@section('content')

{{-- Hero Section --}}
<section class="relative overflow-hidden py-16 sm:py-24 bg-gradient-to-b from-white to-[#F8F9FB] border-b border-slate-200/80 text-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-extrabold uppercase tracking-widest text-brand-red">ABOUT THE PLATFORM</span>
        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-brand-charcoal tracking-tight mt-3 mb-6">
            Engineered for the love and progress of sport.
        </h1>
        <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">
            AmaX is a next-generation sports analytics and real-time match scoring network connecting players, clubs, scouts, and fans worldwide.
        </p>
    </div>
</section>

{{-- Mission & Values --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <x-card class="space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-red-50 text-brand-red flex items-center justify-center text-2xl font-black border border-red-100">
                🎯
            </div>
            <h2 class="text-lg font-black text-brand-charcoal">Our Mission</h2>
            <p class="text-sm text-slate-500 leading-relaxed">
                To make professional sports analytics accessible to every athlete — from grassroots cricket tournaments to national academy selections. Every match deserves accurate, real-time tracking.
            </p>
        </x-card>

        <x-card class="space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-brand-gold flex items-center justify-center text-2xl font-black border border-amber-100">
                🌐
            </div>
            <h2 class="text-lg font-black text-brand-charcoal">One Unified Account</h2>
            <p class="text-sm text-slate-500 leading-relaxed">
                Your athlete profile works seamlessly across our web portal and mobile application. Update your stats once, and your verifiable sports CV is instantly ready for clubs and recruiters.
            </p>
        </x-card>

        <x-card class="space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center text-2xl font-black border border-slate-200">
                📡
            </div>
            <h2 class="text-lg font-black text-brand-charcoal">Instant Scoring Sync</h2>
            <p class="text-sm text-slate-500 leading-relaxed">
                Powered by modern cloud sync architecture, scoring updates from certified match officials reach viewers, commentators, and player profiles instantaneously.
            </p>
        </x-card>
    </div>
</section>

{{-- Sports Grid --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 border-t border-slate-200/80">
    <div class="text-center max-w-2xl mx-auto mb-12">
        <span class="text-xs font-extrabold uppercase tracking-widest text-brand-red">MULTI-SPORT COVERAGE</span>
        <h2 class="text-2xl sm:text-3xl font-black text-brand-charcoal tracking-tight mt-1">20+ Disciplines Supported</h2>
        <p class="text-sm text-slate-500 mt-2">Comprehensive player profiles and customized scoring systems for each sport.</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach([
            ['🏏', 'Cricket', 'Batting, bowling, fielding & strike rates'],
            ['⚽', 'Football', 'Goals, assists, cards & match records'],
            ['🏀', 'Basketball', 'Points, rebounds, assists & fouls'],
            ['🏑', 'Hockey', 'Goals, saves, penalty corners'],
            ['🏐', 'Volleyball', 'Sets, spikes, service aces & blocks'],
            ['🏖️', 'Beach Volleyball', 'Sets, rally points & match records'],
            ['🎾', 'Tennis', 'Sets, games, break points & aces'],
            ['🏸', 'Badminton', 'Sets, rallies, smashes & tournament rank'],
            ['🏓', 'Table Tennis', 'Game sets, service win rate'],
            ['🏊', 'Swimming', 'Personal bests, stroke & heat events'],
            ['🏃', 'Athletics', 'Track & field personal records'],
            ['🥊', 'Boxing', 'Bout records, knockouts & weight class'],
            ['🥋', 'Judo', 'Ippon, waza-ari & tournament medals'],
            ['🥋', 'Karate', 'Styles, bout points & rankings'],
            ['🤼', 'Kabaddi', 'Raids, tackles & bonus points'],
            ['🏉', 'Rugby', 'Tries, conversions & tackles'],
            ['🥅', 'Netball', 'Shooting accuracy & intercepts'],
            ['⚾', 'Baseball', 'Batting averages & pitching ERA'],
            ['♟️', 'Chess', 'FIDE ratings & tournament standings'],
            ['🏏', 'Elle', 'Points, rounds & local championships'],
        ] as [$emoji, $sport, $desc])
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-2xs hover:border-brand-red/40 hover:-translate-y-0.5 transition-all">
                <span class="text-2xl block mb-2">{{ $emoji }}</span>
                <h3 class="font-bold text-sm text-slate-900">{{ $sport }}</h3>
                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">{{ $desc }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- Join Call to Action --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-gradient-to-r from-brand-charcoal to-slate-900 rounded-3xl p-8 sm:p-12 text-center text-white shadow-lg">
        <h2 class="text-2xl sm:text-3xl font-black mb-3">Start Building Your Sports Profile</h2>
        <p class="text-slate-300 text-sm max-w-md mx-auto mb-6">
            Free to register. Take control of your sports credentials and get discovered.
        </p>
        <x-button href="{{ route('register') }}" variant="primary" size="lg">
            Create Free Account
        </x-button>
    </div>
</section>

@endsection

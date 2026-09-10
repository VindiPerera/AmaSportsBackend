@extends('public.layouts.app')

@section('title', 'About AmaX')
@section('meta_description', 'Learn about AmaX — the platform built to bring every sport live to fans, coaches and athletes across web and mobile.')

@section('content')

{{-- Hero --}}
<section style="position: relative; overflow: hidden; padding: 5rem 1.5rem 3.5rem; text-align: center;">
    <div style="position: absolute; inset: 0; background: radial-gradient(ellipse 70% 50% at 50% 0%, rgba(99,102,241,0.12) 0%, transparent 70%); pointer-events: none;"></div>
    <div style="position: relative; max-width: 720px; margin: 0 auto;">
        <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.25); border-radius: 2rem; padding: 0.375rem 1rem; font-size: 0.75rem; font-weight: 700; color: #818cf8; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 1.5rem;">
            About the Platform
        </div>
        <h1 style="font-weight: 900; font-size: clamp(2rem, 5vw, 3.25rem); color: #fff; line-height: 1.1; letter-spacing: -0.03em; margin-bottom: 1.25rem;">
            Built for the love of sport
        </h1>
        <p style="font-size: 1.0625rem; color: rgba(148,163,184,0.85); line-height: 1.75; max-width: 560px; margin: 0 auto;">
            AmaX is a comprehensive sports analytics and live scoring platform connecting players, coaches, and fans across every sport — available on both web and mobile.
        </p>
    </div>
</section>

{{-- Mission --}}
<section style="max-width: 1280px; margin: 0 auto; padding: 2rem 1.5rem 3rem;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">

        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.25rem; padding: 2rem;">
            <div style="font-size: 2rem; margin-bottom: 1rem;">🎯</div>
            <h2 style="font-weight: 800; font-size: 1.125rem; color: #fff; margin-bottom: 0.75rem;">Our Mission</h2>
            <p style="font-size: 0.875rem; color: rgba(148,163,184,0.75); line-height: 1.7;">
                To make sports data accessible to everyone — from grassroots athletes to professional coaches. We believe every match deserves to be followed in real time, with the same quality experience on mobile and web.
            </p>
        </div>

        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.25rem; padding: 2rem;">
            <div style="font-size: 2rem; margin-bottom: 1rem;">🌐</div>
            <h2 style="font-weight: 800; font-size: 1.125rem; color: #fff; margin-bottom: 0.75rem;">One Account, Every Platform</h2>
            <p style="font-size: 0.875rem; color: rgba(148,163,184,0.75); line-height: 1.7;">
                Your AmaX account works seamlessly across the mobile app and web portal. Log in once and pick up right where you left off — your profile, stats, and subscriptions follow you everywhere.
            </p>
        </div>

        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.25rem; padding: 2rem;">
            <div style="font-size: 2rem; margin-bottom: 1rem;">📡</div>
            <h2 style="font-weight: 800; font-size: 1.125rem; color: #fff; margin-bottom: 0.75rem;">Real-Time Firebase Sync</h2>
            <p style="font-size: 0.875rem; color: rgba(148,163,184,0.75); line-height: 1.7;">
                Live scores are powered by Firebase Realtime Database, meaning updates from scorers reach every viewer instantly — no refreshing required.
            </p>
        </div>

    </div>
</section>

{{-- Sports grid --}}
<section style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06); padding: 3.5rem 1.5rem;">
    <div style="max-width: 1280px; margin: 0 auto; text-align: center;">
        <h2 style="font-weight: 800; font-size: 1.5rem; color: #fff; margin-bottom: 0.5rem;">20+ Sports. One App.</h2>
        <p style="color: rgba(148,163,184,0.65); font-size: 0.875rem; margin-bottom: 2rem;">Complete player profiles, career stats, and live scoring for every sport below.</p>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 0.875rem; text-align: left;">
            @foreach([
                ['🏏', 'Cricket',         'Batting, bowling & fielding stats'],
                ['⚽', 'Football',        'Goals, assists, match stats'],
                ['🏀', 'Basketball',      'Points, rebounds, assists'],
                ['🏑', 'Hockey',          'Goals, saves, penalty corners'],
                ['🏐', 'Volleyball',      'Sets, spikes, blocks'],
                ['🏐', 'Beach Volleyball','Sets and serve stats'],
                ['🎾', 'Tennis',          'Sets, games, aces'],
                ['🏸', 'Badminton',       'Sets, rallies, smashes'],
                ['🏓', 'Table Tennis',    'Sets and service stats'],
                ['🏊', 'Swimming',        'Personal bests, events'],
                ['🏃', 'Athletics',       'Events, personal records'],
                ['🥊', 'Boxing',          'Wins, KOs, weight class'],
                ['🥋', 'Judo',            'Ippons, waza-ari, shidos'],
                ['🥋', 'Karate',          'Style, bouts, scores'],
                ['🤸', 'Kabaddi',         'Raids, tackles, points'],
                ['🏉', 'Rugby',           'Tries, conversions, tackles'],
                ['🏐', 'Netball',         'Goals, intercepts, assists'],
                ['⚾', 'Baseball',        'Batting, pitching, ERA'],
                ['♟️', 'Chess',           'Rating, openings, results'],
                ['🏋️', 'Elle',            'Points, rounds, rankings'],
            ] as [$emoji, $sport, $desc])
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 0.875rem; padding: 1rem; transition: all 0.2s;"
                 onmouseover="this.style.background='rgba(255,255,255,0.06)'; this.style.borderColor='rgba(255,255,255,0.14)';"
                 onmouseout="this.style.background='rgba(255,255,255,0.03)'; this.style.borderColor='rgba(255,255,255,0.07)';">
                <div style="font-size: 1.375rem; margin-bottom: 0.5rem;">{{ $emoji }}</div>
                <div style="font-weight: 700; font-size: 0.875rem; color: #e2e8f0; margin-bottom: 0.25rem;">{{ $sport }}</div>
                <div style="font-size: 0.75rem; color: rgba(100,116,139,0.7); line-height: 1.4;">{{ $desc }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section style="max-width: 700px; margin: 0 auto; padding: 4rem 1.5rem;">
    <div style="background: linear-gradient(135deg, rgba(245,158,11,0.1), rgba(99,102,241,0.1)); border: 1px solid rgba(245,158,11,0.2); border-radius: 1.5rem; padding: 3rem 2rem; text-align: center;">
        <h2 style="font-weight: 900; font-size: 1.625rem; color: #fff; margin-bottom: 0.75rem;">Join AmaX today</h2>
        <p style="color: rgba(148,163,184,0.8); font-size: 0.9375rem; margin-bottom: 1.75rem;">Free to sign up. Track your sport. Follow your athletes.</p>
        <a href="{{ route('register') }}" class="btn-primary" style="padding: 0.75rem 2rem; font-size: 1rem;">Create Free Account</a>
    </div>
</section>

@endsection

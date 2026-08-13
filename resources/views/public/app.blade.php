@extends('public.layouts.app')

@section('title', 'Mobile App Web Preview — AmaX')

@section('content')
<section style="padding: 2rem 1.5rem 4rem; text-align: center;">
    <div style="max-width: 600px; margin: 0 auto 1.5rem;">
        <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.25); border-radius: 2rem; padding: 0.375rem 1rem; font-size: 0.75rem; font-weight: 700; color: #fbbf24; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 1rem;">
            📱 Mobile Web App Preview
        </div>
        <h1 style="font-weight: 900; font-size: 1.75rem; color: #fff; margin-bottom: 0.5rem;">AmaX Mobile Experience</h1>
        <p style="font-size: 0.875rem; color: rgba(148,163,184,0.75);">Preview player profile management, live scores, and mobile features in real-time.</p>
    </div>

    {{-- Phone device frame --}}
    <div style="width: 100%; max-width: 410px; height: 820px; margin: 0 auto; background: #000; border: 12px solid #1e293b; border-radius: 44px; overflow: hidden; box-shadow: 0 25px 60px -15px rgba(0,0,0,0.8); position: relative;">
        {{-- Speaker notch --}}
        <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 140px; height: 24px; background: #1e293b; border-bottom-left-radius: 14px; border-bottom-right-radius: 14px; z-index: 10; display: flex; align-items: center; justify-content: center;">
            <div style="width: 40px; height: 4px; background: #334155; border-radius: 2px;"></div>
        </div>

        <iframe src="/mobile-preview/" style="width: 100%; height: 100%; border: none; background: #000;" title="AmaX Mobile App"></iframe>
    </div>
</section>
@endsection

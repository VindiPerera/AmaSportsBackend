@extends('public.layouts.app')

@section('title', 'Contact Us — AmaX')
@section('meta_description', 'Get in touch with the AmaX team. We\'d love to hear from you.')

@section('content')

<section style="max-width: 900px; margin: 0 auto; padding: 4rem 1.5rem;">

    {{-- Header --}}
    <div style="text-align: center; margin-bottom: 3rem;">
        <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); border-radius: 2rem; padding: 0.375rem 1rem; font-size: 0.75rem; font-weight: 700; color: #34d399; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 1.25rem;">
            Get In Touch
        </div>
        <h1 style="font-weight: 900; font-size: clamp(1.875rem, 5vw, 3rem); color: #fff; line-height: 1.1; letter-spacing: -0.03em; margin-bottom: 1rem;">We'd love to hear from you</h1>
        <p style="font-size: 1rem; color: rgba(148,163,184,0.8); max-width: 480px; margin: 0 auto; line-height: 1.7;">
            Have a question, feedback or a partnership enquiry? Drop us a message and we'll get back to you shortly.
        </p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; align-items: start;">

        {{-- Left — Contact Info --}}
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @foreach([
                ['📧', 'Email', 'hello@amax.com', null],
                ['📱', 'Mobile', 'Available on iOS & Android', null],
                ['🌐', 'Web', 'amax.com', null],
            ] as [$icon, $label, $value, $href])
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1rem; padding: 1.25rem; display: flex; align-items: flex-start; gap: 0.875rem;">
                <div style="font-size: 1.25rem; flex-shrink: 0; margin-top: 0.1rem;">{{ $icon }}</div>
                <div>
                    <div style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(100,116,139,0.7); margin-bottom: 0.25rem;">{{ $label }}</div>
                    <div style="font-size: 0.875rem; font-weight: 600; color: #cbd5e1;">{{ $value }}</div>
                </div>
            </div>
            @endforeach

            <div style="background: linear-gradient(135deg, rgba(245,158,11,0.08), rgba(99,102,241,0.08)); border: 1px solid rgba(245,158,11,0.15); border-radius: 1rem; padding: 1.25rem; margin-top: 0.5rem;">
                <p style="font-size: 0.8125rem; color: rgba(148,163,184,0.75); line-height: 1.65;">
                    Want to get on the app? <a href="{{ route('register') }}" style="color: #f59e0b; font-weight: 700; text-decoration: none;">Create an account</a> and start tracking your favourite sports today.
                </p>
            </div>
        </div>

        {{-- Right — Contact Form --}}
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.25rem; padding: 2rem;">

            @if(session('success'))
                <div class="flash-success">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="flash-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('public.contact.store') }}" style="display: flex; flex-direction: column; gap: 1.25rem;">
                @csrf

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: rgba(203,213,225,0.85); margin-bottom: 0.5rem;" for="contact-name">Your Name</label>
                        <input id="contact-name" type="text" name="name" value="{{ old('name') }}" required placeholder="John Smith" class="form-input">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: rgba(203,213,225,0.85); margin-bottom: 0.5rem;" for="contact-email">Email Address</label>
                        <input id="contact-email" type="email" name="email" value="{{ old('email') }}" required placeholder="john@example.com" class="form-input">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: rgba(203,213,225,0.85); margin-bottom: 0.5rem;" for="contact-message">Message</label>
                    <textarea id="contact-message" name="message" required rows="6" placeholder="Tell us how we can help you..." class="form-input" style="resize: vertical; min-height: 120px;">{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="btn-primary" style="align-self: flex-start; padding: 0.75rem 2rem; font-size: 0.9375rem;">
                    Send Message
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                </button>
            </form>
        </div>

    </div>

</section>

<style>
    @media (max-width: 640px) {
        section > div[style*="grid-template-columns: 1fr 2fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>

@endsection

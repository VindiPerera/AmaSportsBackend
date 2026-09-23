@extends('public.layouts.app')

@section('title', 'Contact Us — AmaX')
@section('meta_description', 'Get in touch with the AmaX team. Inquiries, partnership proposals, and support.')

@section('content')

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">

    {{-- Page Header --}}
    <div class="text-center max-w-2xl mx-auto mb-12">
        <span class="text-xs font-extrabold uppercase tracking-widest text-brand-red">GET IN TOUCH</span>
        <h1 class="text-3xl sm:text-5xl font-black text-brand-charcoal tracking-tight mt-2 mb-4">
            We would love to hear from you.
        </h1>
        <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
            Have a question about the platform, club integration, or technical support? Send us a message and our team will get back to you promptly.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        {{-- Left: Contact Details --}}
        <div class="space-y-4">
            @foreach([
                ['📧', 'Email Support', 'alex@amaxlk.com', 'mailto:alex@amaxlk.com'],
                ['📞', 'Phone Helpline', '+94 75 220 6006', 'tel:+94752206006'],
                ['📍', 'Headquarters', 'AmaX, Sutton Indoor Cricket Center, 29 Maitland Place, Colombo 07, Sri Lanka', null],
                ['📱', 'Mobile Platform', 'Available on iOS and Android App Stores', null],
            ] as [$icon, $title, $detail, $link])
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs">
                    <div class="text-2xl mb-2">{{ $icon }}</div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">{{ $title }}</span>
                    @if($link)
                        <a href="{{ $link }}" class="text-sm font-bold text-brand-charcoal hover:text-brand-red transition-colors block mt-0.5">{{ $detail }}</a>
                    @else
                        <span class="text-sm font-semibold text-slate-700 block mt-0.5 leading-relaxed">{{ $detail }}</span>
                    @endif
                </div>
            @endforeach

            <div class="bg-amber-50 rounded-2xl border border-amber-200 p-5 text-amber-950">
                <span class="text-xs font-bold uppercase tracking-wider block mb-1">Looking to Register?</span>
                <p class="text-xs leading-relaxed text-amber-900">
                    Athletes and club administrators can register immediately to start tracking matches and creating profile sheets.
                </p>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-1 text-xs font-extrabold text-brand-red hover:underline mt-2">
                    <span>Create Free Account</span>
                    <span>→</span>
                </a>
            </div>
        </div>

        {{-- Right: Contact Message Form --}}
        <div class="lg:col-span-2">
            <x-card>
                <x-slot:header>
                    <h3 class="font-bold text-slate-900 text-base">Send Us a Direct Message</h3>
                </x-slot:header>

                @if(session('success'))
                    <x-alert type="success" title="Message Sent">
                        {{ session('success') }}
                    </x-alert>
                @endif

                @if($errors->any())
                    <x-alert type="error" title="Submission Error">
                        <ul class="list-disc pl-5 space-y-1 text-xs">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-alert>
                @endif

                <form method="POST" action="{{ route('public.contact.store') }}" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-input
                            name="name"
                            label="Your Full Name"
                            required
                            placeholder="e.g. John Smith"
                        />

                        <x-input
                            type="email"
                            name="email"
                            label="Email Address"
                            required
                            placeholder="e.g. john@example.com"
                        />
                    </div>

                    <x-textarea
                        name="message"
                        label="Message"
                        required
                        rows="5"
                        placeholder="Tell us about your inquiry, club requirements, or feedback..."
                    />

                    <div class="pt-2">
                        <x-button type="submit" variant="primary" size="lg">
                            <span>Send Message</span>
                            <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </x-button>
                    </div>
                </form>
            </x-card>
        </div>

    </div>

</section>

@endsection

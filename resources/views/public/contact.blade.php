@extends('layouts.public')

@section('title', $siteSettings->get('contact_header_title', 'Kontak'))

@section('content')
{{-- Page wrapper — navbar clearance shares same bg as header --}}
<div class="min-h-screen bg-gray-50 dark:bg-slate-800 transition-colors duration-300">

{{-- Header --}}
<section class="pt-24 sm:pt-32 lg:pt-40 pb-12 sm:pb-16 lg:pb-20 transition-colors duration-300">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="text-xs uppercase tracking-[0.2em] text-primary-600 dark:text-primary-400 font-bold mb-4 block transition-colors bg-primary-50 dark:bg-primary-900/30 w-fit mx-auto px-4 py-1.5 rounded-full">{{ $siteSettings->get('contact_header_tagline', 'Hubungi Kami') }}</span>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-serif font-bold text-gray-900 dark:text-white mb-6 transition-colors">{{ $siteSettings->get('contact_header_title', 'Kontak') }}</h1>
        <p class="text-base sm:text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto transition-colors leading-relaxed">{{ $siteSettings->get('contact_header_description', 'Tertarik untuk bekerja sama atau memiliki pertanyaan? Kami siap membantu dan merespons setiap inquiry Anda.') }}</p>
    </div>
</section>

{{-- Contact Content --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-12 sm:py-16 lg:py-24">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20">
        {{-- Contact Form --}}
        <div class="lg:col-span-7">
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white mb-6 transition-colors">{{ $siteSettings->get('contact_form_title', 'Kirim Pesan') }}</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-8 transition-colors">{{ $siteSettings->get('contact_form_description', 'Tulis pesan Anda dan tim kami akan segera menghubungi Anda.') }}</p>
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 sm:p-8 border border-gray-100 dark:border-slate-700 transition-colors">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}">
                    @csrf
                    <div class="space-y-4 sm:space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 transition-colors">Nama <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-white px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition @error('name') border-red-400 @enderror"
                                   placeholder="Nama lengkap Anda">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 transition-colors">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-white px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition @error('email') border-red-400 @enderror"
                                   placeholder="email@contoh.com">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 transition-colors">Subjek</label>
                            <input type="text" name="subject" value="{{ old('subject') }}"
                                   class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-white px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                   placeholder="Apa yang bisa kami bantu?">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 transition-colors">Pesan <span class="text-red-500">*</span></label>
                            <textarea name="message" rows="5"
                                      class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-white px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition @error('message') border-red-400 @enderror"
                                      placeholder="Tulis pesan Anda di sini...">{{ old('message') }}</textarea>
                            @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 rounded-xl transition duration-200">
                            {{ $siteSettings->get('contact_button_text', 'Kirim Pesan') }}
                        </button>
                    </div>
                </form>
            </div>

        {{-- Contact Info Card - Modern Design --}}
        <div class="lg:col-span-5">
            {{-- Main Contact Card --}}
            <div class="relative overflow-hidden rounded-[2rem] shadow-2xl transition-colors h-full"
                 :class="darkMode ? 'bg-gradient-to-br from-slate-800 via-slate-900 to-slate-800 border border-slate-700' : 'bg-gradient-to-br from-primary-600 via-primary-700 to-blue-800'">
                {{-- Decorative circles --}}
                <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full opacity-10 bg-white"></div>
                <div class="absolute -bottom-8 -left-8 w-32 h-32 rounded-full opacity-10 bg-white"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 rounded-full opacity-5 bg-white"></div>

                <div class="relative z-10 p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Informasi Kontak</h2>
                            <p class="text-white/70 text-xs">Kami siap membantu Anda</p>
                        </div>

                    <div class="space-y-5">
                        {{-- Address --}}
                        @php $address = $siteSettings->get('address', 'Jl. DPR V Cileunyi Kulon, Kec. Cileunyi, Kabupaten Bandung, Jawa Barat 40622'); @endphp
                        @if($address)
                        <div class="flex gap-4 items-start group">
                            <div class="w-11 h-11 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center flex-shrink-0 group-hover:bg-white/25 transition-colors">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-white/60 text-xs font-medium uppercase tracking-wider mb-1">Alamat</p>
                                <p class="text-white text-sm leading-relaxed">{{ $address }}</p>
                            </div>
                        @endif

                        {{-- Email --}}
                        @if($siteSettings->get('email'))
                        <div class="flex gap-4 items-start group">
                            <div class="w-11 h-11 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center flex-shrink-0 group-hover:bg-white/25 transition-colors">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-white/60 text-xs font-medium uppercase tracking-wider mb-1">Email</p>
                                <a href="mailto:{{ $siteSettings->get('email') }}" class="text-white text-sm font-medium hover:text-white/80 transition break-all">{{ $siteSettings->get('email') }}</a>
                            </div>
                        @endif

                        {{-- WhatsApp --}}
                        @if($siteSettings->get('wa_number'))
                        <div class="flex gap-4 items-start group">
                            <div class="w-11 h-11 rounded-2xl bg-green-500/30 backdrop-blur-sm flex items-center justify-center flex-shrink-0 group-hover:bg-green-500/50 transition-colors">
                                <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            </div>
                            <div>
                                <p class="text-white/60 text-xs font-medium uppercase tracking-wider mb-1">WhatsApp</p>
                                <a href="https://wa.me/{{ $siteSettings->get('wa_number') }}" target="_blank" rel="noopener" class="text-white text-sm font-medium hover:text-green-300 transition">
                                    0895611314372
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Social Media --}}
                    @if($siteSettings->get('facebook') || $siteSettings->get('instagram') || $siteSettings->get('tiktok') || $siteSettings->get('youtube'))
                    <div class="mt-6 pt-6 border-t border-white/20">
                        <p class="text-white/60 text-xs font-medium uppercase tracking-wider mb-4">Ikuti Kami</p>
                        <div class="flex gap-3 flex-wrap">
                            @if($siteSettings->get('facebook'))
                            <a href="{{ $siteSettings->get('facebook') }}" target="_blank" rel="noopener"
                               class="w-10 h-10 rounded-xl bg-white/15 hover:bg-blue-500 flex items-center justify-center transition-all duration-300 hover:scale-110 group"
                               title="Facebook">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            @endif
                            @if($siteSettings->get('instagram'))
                            <a href="{{ $siteSettings->get('instagram') }}" target="_blank" rel="noopener"
                               class="w-10 h-10 rounded-xl bg-white/15 hover:bg-gradient-to-br hover:from-purple-600 hover:via-pink-500 hover:to-orange-400 flex items-center justify-center transition-all duration-300 hover:scale-110"
                               title="Instagram">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </a>
                            @endif
                            @if($siteSettings->get('tiktok'))
                            <a href="{{ $siteSettings->get('tiktok') }}" target="_blank" rel="noopener"
                               class="w-10 h-10 rounded-xl bg-white/15 hover:bg-gray-900 flex items-center justify-center transition-all duration-300 hover:scale-110"
                               title="TikTok">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1v-3.5a6.37 6.37 0 00-.79-.05A6.34 6.34 0 003.15 15.2a6.34 6.34 0 0010.86 4.48 6.3 6.3 0 001.88-4.48V8.75a8.26 8.26 0 004.7 1.46V6.77a4.84 4.84 0 01-1-.08z"/></svg>
                            </a>
                            @endif
                            @if($siteSettings->get('youtube'))
                            <a href="{{ $siteSettings->get('youtube') }}" target="_blank" rel="noopener"
                               class="w-10 h-10 rounded-xl bg-white/15 hover:bg-red-600 flex items-center justify-center transition-all duration-300 hover:scale-110"
                               title="YouTube">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </a>
                            @endif
                        </div>
                    @endif

                    {{-- WhatsApp CTA button --}}
                    @if($siteSettings->get('wa_number'))
                    <div class="mt-6">
                        <a href="https://wa.me/{{ $siteSettings->get('wa_number') }}" target="_blank" rel="noopener"
                           class="w-full flex items-center justify-center gap-2.5 bg-green-500 hover:bg-green-400 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 hover:shadow-lg hover:shadow-green-500/30 hover:-translate-y-0.5 active:translate-y-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Hubungi via WhatsApp Sekarang
                        </a>
                    </div>
                    @endif
                </div>

            @if($siteSettings->get('contact_show_map'))
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-700 transition-colors">
                <h3 class="font-semibold text-gray-900 dark:text-white text-base mb-4 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    Lokasi Kami
                </h3>
                <div class="bg-gray-100 dark:bg-slate-700 rounded-2xl overflow-hidden h-64 sm:h-72 shadow-lg transition-colors border border-gray-200 dark:border-slate-600">
                    @if($siteSettings->get('contact_map_embed'))
                        {!! $siteSettings->get('contact_map_embed') !!}
                    @else
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.752862999098!2d107.216888!3d-6.901849!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6a5fbb42691%3A0x1234567890abcdef!2sJl.%20DPR%20V%20Cileunyi%20Kulon%2C%20Cileunyi%2C%20Bandung%20Regency%2C%20West%20Java%2040622!5e0!3m2!1sen!2sid!4v1713600000000"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    @endif
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-3 text-center transition-colors font-medium">📍 {{ $siteSettings->get('address', 'Jl. DPR V Cileunyi Kulon, Kec. Cileunyi, Kabupaten Bandung, Jawa Barat 40622') }}</p>
            </div>
            @endif
        </div>
</div>

{{-- CTA Section --}}
<section class="bg-gradient-to-r from-primary-600 to-primary-700 py-12 sm:py-16 lg:py-20 transition-colors">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-serif font-bold mb-4 text-white uppercase tracking-tight">{{ $siteSettings->get('contact_cta_title', 'Siap untuk Berkolaborasi?') }}</h2>
        <p class="text-base sm:text-lg text-white/95 max-w-2xl mx-auto mb-8 leading-relaxed">{{ $siteSettings->get('contact_cta_description', 'Hubungi kami sekarang melalui salah satu saluran komunikasi di bawah ini. Tim kami siap merespons pertanyaan Anda dengan cepat.') }}</p>
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-center w-full">
            @if($siteSettings->get('email'))
            <a href="mailto:{{ $siteSettings->get('email') }}" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-white text-primary-600 hover:bg-gray-100 text-sm sm:text-base font-medium px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg transition duration-200">
                <ion-icon name="mail-outline" class="text-lg"></ion-icon>
                {{ $siteSettings->get('contact_cta_email_text', 'Kirim Email') }}
            </a>
            @endif
            @if($siteSettings->get('wa_number'))
            <a href="https://wa.me/{{ $siteSettings->get('wa_number') }}" target="_blank" rel="noopener" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-green-500 text-white hover:bg-green-600 text-sm sm:text-base font-medium px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg transition duration-200">
                <ion-icon name="logo-whatsapp" class="text-lg"></ion-icon>
                {{ $siteSettings->get('contact_cta_whatsapp_text', 'Hubungi WhatsApp') }}
            </a>
            @endif
        </div>
</section>

</div>{{-- end page wrapper --}}
@endsection

@extends('layouts.public')

@section('title')
{{ $service->title }}
@endsection

@section('meta_description')
{{ $service->excerpt ?? '' }}
@endsection

@section('content')
<div class="relative w-full overflow-hidden" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }">

    {{-- Hero Section — TIDAK DIUBAH --}}
    <div class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-blue-800 dark:from-slate-900 dark:via-primary-900 dark:to-slate-900 py-16 sm:py-20 lg:py-24">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)"/>
            </svg>
        </div>
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <nav class="text-sm mb-6 text-white/70">
                <a href="{{ route('services.index') }}" class="hover:text-white transition">Layanan</a>
                <span class="mx-2">/</span>
                <span class="text-white">{{ $service->title }}</span>
            </nav>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                @if($service->icon)
                    <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center flex-shrink-0">
                        <div class="text-5xl">
                            @if(preg_match('/^[a-z-]+$/i', $service->icon))
                                <ion-icon name="{{ $service->icon }}" class="text-white"></ion-icon>
                            @else
                                {{ $service->icon }}
                            @endif
                        </div>
                    </div>
                @endif
                <div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-3 leading-tight">{{ $service->title }}</h1>
                    @if($service->excerpt)
                        <p class="text-lg text-primary-100 max-w-2xl">{{ $service->excerpt }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="bg-white dark:bg-slate-900 transition-colors duration-300">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 lg:gap-14">

                {{-- Main — 2/3 width --}}
                <div class="lg:col-span-2">

                    {{-- Body --}}
                    @if($service->body)
                    <div class="prose prose-lg max-w-none leading-relaxed service-content transition-colors duration-300 mb-10"
                         :class="darkMode ? 'prose-invert' : ''">
                        {!! $service->body !!}
                    </div>
                    <style>
                        .service-content{color:inherit}
                        .service-content p{margin-bottom:1.5rem;line-height:1.8}
                        .service-content h2{margin-top:2.5rem;margin-bottom:1rem;font-weight:700;font-size:1.5rem;padding-bottom:.5rem;border-bottom:2px solid #e5e7eb}
                        .dark .service-content h2{border-color:#374151}
                        .service-content h3{margin-top:1.5rem;margin-bottom:.75rem;font-weight:600;font-size:1.25rem}
                        .service-content ul,.service-content ol{margin-bottom:1.5rem;padding-left:1.5rem}
                        .service-content li{margin-bottom:.75rem;line-height:1.7}
                        .service-content li::marker{color:#2563eb}
                        .dark .service-content li::marker{color:#7c3aed}
                        .service-content img{border-radius:.75rem;margin-top:2rem;margin-bottom:2rem;box-shadow:0 4px 6px -1px rgba(0,0,0,.1)}
                        .service-content blockquote{margin:2rem 0;padding:1.25rem 1.5rem;border-left:4px solid #2563eb;background:rgba(37,99,235,.05);border-radius:0 .5rem .5rem 0;font-style:italic}
                        .dark .service-content blockquote{border-color:#7C3AED;background-color:rgba(124,58,237,.08)}
                        .service-content table{border-collapse:collapse;width:100%;margin-bottom:1.5rem}
                        .service-content th,.service-content td{border:1px solid #e5e7eb;padding:.875rem 1rem;text-align:left}
                        .dark .service-content th,.dark .service-content td{border-color:#374151}
                        .service-content th{background-color:#f9fafb;font-weight:600;color:#374151}
                        .dark .service-content th{background-color:#1f2937;color:#e5e7eb}
                        .service-content tr:nth-child(even){background-color:#f9fafb}
                        .dark .service-content tr:nth-child(even){background-color:rgba(31,41,55,.5)}
                        .dark .service-content{color:rgb(229,231,235)}
                        .dark .service-content h2,.dark .service-content h3{color:rgb(243,244,246)}
                        .dark .service-content strong{color:rgb(243,244,246)}
                        .service-content a{color:#2563eb;text-decoration:underline;text-underline-offset:2px}
                        .dark .service-content a{color:#60a5fa}
                        .service-content code{background:#f3f4f6;padding:.2rem .4rem;border-radius:.25rem;font-size:.875em;color:#dc2626}
                        .dark .service-content code{background:#374151;color:#f87171}
                        .service-content pre{background:#1f2937;color:#e5e7eb;padding:1.25rem;border-radius:.5rem;overflow-x:auto;margin-bottom:1.5rem}
                    </style>
                    @endif

                    {{-- Koleksi Buku --}}
                    @if($publishedBooks && $publishedBooks->count() > 0)
                    <div class="pt-8 border-t transition-colors duration-300" :class="darkMode ? 'border-slate-700' : 'border-gray-100'">
                        <div class="flex items-end justify-between mb-5">
                            <div>
                                <h2 class="text-lg font-bold transition-colors" :class="darkMode ? 'text-white' : 'text-gray-900'">Koleksi Buku Kami</h2>
                                <p class="text-xs mt-0.5 transition-colors" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">Karya ilmiah yang telah kami terbitkan</p>
                            </div>
                            <a href="{{ route('books.index') }}"
                               class="text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1 flex-shrink-0">
                                Lihat Semua
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">                            @foreach($publishedBooks as $book)
                            <a href="{{ route('books.show', $book->slug) }}"
                               class="group flex flex-col rounded-xl overflow-hidden border transition-all duration-300 hover:shadow-md hover:-translate-y-0.5"
                               :class="darkMode ? 'border-slate-700 bg-slate-800 hover:border-slate-600' : 'border-gray-200 bg-white hover:border-gray-300'">
                                <div class="aspect-[3/4] overflow-hidden bg-gray-100 dark:bg-slate-700 flex-shrink-0">
                                    @if($book->cover_url)
                                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <span class="text-3xl opacity-30">📖</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-3 flex flex-col gap-1">
                                    @if($book->category)
                                        <span class="text-[9px] font-bold uppercase tracking-wider text-primary-600 dark:text-primary-400">{{ $book->category->name }}</span>
                                    @endif
                                    <h4 class="text-xs font-semibold leading-snug line-clamp-2 transition-colors group-hover:text-primary-600 dark:group-hover:text-primary-400"
                                        :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $book->title }}</h4>
                                    <p class="text-[10px] truncate transition-colors"
                                       :class="darkMode ? 'text-gray-400' : 'text-gray-500'">{{ $book->author }}</p>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Sidebar — 1/3 width --}}
                <aside class="lg:col-span-1">
                    <div class="lg:sticky lg:top-28 space-y-5">

                        {{-- CTA Card — hanya tampil di desktop (lg+), di mobile dipindah ke bawah pricing --}}
                        <div class="hidden lg:block rounded-2xl p-6 bg-gradient-to-br from-primary-600 to-blue-700 text-white">
                            <h3 class="text-base font-bold mb-2">Tertarik dengan Layanan Ini?</h3>
                            <p class="text-sm text-primary-100 mb-5">Konsultasikan kebutuhan Anda dengan tim kami.</p>
                            <div class="space-y-2.5">
                                <a href="{{ route('contact') }}?service={{ $service->slug }}"
                                   class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-white text-primary-600 text-sm font-bold hover:bg-primary-50 transition">
                                    <ion-icon name="chatbubble-ellipses-outline"></ion-icon>
                                    Konsultasi Sekarang
                                </a>
                                <a href="https://wa.me/62895611314372?text=Halo,%20saya%20ingin%20konsultasi%20mengenai%20layanan%20{{ urlencode($service->title) }}"
                                   target="_blank" rel="noopener"
                                   class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-green-500 text-white text-sm font-bold hover:bg-green-600 transition">
                                    <ion-icon name="logo-whatsapp"></ion-icon>
                                    WhatsApp
                                </a>
                            </div>
                        </div>

                    </div>
                </aside>

            </div>
        </div>

        {{-- Pricing Plans --}}
        @php
            $plans = ($service->plans ?? collect())->where('is_active', true)->sortBy('order')->values();
        @endphp
        @if($plans->count() > 0)
        <div class="border-t transition-colors duration-300" :class="darkMode ? 'border-slate-800 bg-slate-900/50' : 'border-gray-100 bg-gray-50'">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
                <div class="text-center mb-10">
                    <h2 class="text-2xl font-bold transition-colors" :class="darkMode ? 'text-white' : 'text-gray-900'">Pilihan Paket</h2>
                    <p class="text-sm mt-2 text-gray-500 dark:text-gray-400">Pilih paket yang paling sesuai dengan kebutuhan publikasi Anda.</p>
                </div>

                <div class="flex md:grid md:grid-cols-3 overflow-x-auto md:overflow-visible pb-4 md:pb-0 -mx-4 px-4 sm:mx-0 sm:px-0 gap-4 snap-x snap-mandatory hide-scrollbar">
                    @foreach($plans as $plan)
                    <div class="flex-none w-[260px] sm:w-[280px] md:w-auto snap-center">
                        <div class="h-full flex flex-col p-6 rounded-2xl border transition-all duration-300
                            {{ $plan->is_popular ? 'border-primary-500 ring-2 ring-primary-500 bg-white dark:bg-slate-800' : 'border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800' }}">

                            @if($plan->is_popular)
                                <span class="inline-block self-start text-[10px] font-bold uppercase tracking-wider bg-primary-600 text-white px-3 py-1 rounded-full mb-4">⭐ Terbaik</span>
                            @endif

                            <h4 class="text-lg font-bold mb-1 transition-colors" :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $plan->name }}</h4>
                            @if($plan->subtitle)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">{{ $plan->subtitle }}</p>
                            @endif

                            <div class="mb-6">
                                <span class="text-3xl font-bold text-primary-600 dark:text-primary-400">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                            </div>

                            @if($plan->features && is_array($plan->features) && count($plan->features) > 0)
                            <ul class="space-y-2.5 mb-6 flex-1">
                                @foreach($plan->features as $feature)
                                    @if($feature)
                                    <li class="flex items-start gap-2.5 text-sm text-gray-600 dark:text-gray-300">
                                        <svg class="w-4 h-4 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        {{ $feature }}
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                            @endif

                            <a href="{{ route('contact') }}?service={{ $service->slug }}&plan={{ $plan->id }}"
                               class="block w-full text-center py-3 rounded-xl text-sm font-bold transition-all
                               {{ $plan->is_popular ? 'bg-primary-600 text-white hover:bg-primary-700 shadow-lg shadow-primary-600/20' : 'bg-gray-100 dark:bg-slate-700 text-gray-900 dark:text-white hover:bg-gray-200 dark:hover:bg-slate-600' }}">
                                Pilih Paket
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <style>
            .hide-scrollbar::-webkit-scrollbar { display: none; }
            .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        </style>
        @endif

        {{-- CTA Mobile — tampil di bawah pricing, hanya di mobile --}}
        <div class="lg:hidden max-w-6xl mx-auto px-4 sm:px-6 py-8">
            <div class="rounded-2xl p-6 bg-gradient-to-br from-primary-600 to-blue-700 text-white">
                <h3 class="text-base font-bold mb-2">Tertarik dengan Layanan Ini?</h3>
                <p class="text-sm text-primary-100 mb-5">Konsultasikan kebutuhan Anda dengan tim kami.</p>
                <div class="space-y-2.5">
                    <a href="{{ route('contact') }}?service={{ $service->slug }}"
                       class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-white text-primary-600 text-sm font-bold hover:bg-primary-50 transition">
                        <ion-icon name="chatbubble-ellipses-outline"></ion-icon>
                        Konsultasi Sekarang
                    </a>
                    <a href="https://wa.me/62895611314372?text=Halo,%20saya%20ingin%20konsultasi%20mengenai%20layanan%20{{ urlencode($service->title) }}"
                       target="_blank" rel="noopener"
                       class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-green-500 text-white text-sm font-bold hover:bg-green-600 transition">
                        <ion-icon name="logo-whatsapp"></ion-icon>
                        WhatsApp
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

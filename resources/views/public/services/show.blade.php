@extends('layouts.public')

@section('title')
{{ $service->title }}
@endsection

@section('meta_description')
{{ $service->excerpt ?? '' }}
@endsection

@section('content')
<div class="relative w-full overflow-hidden">
    {{-- Hero Section --}}
    <div class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-blue-800 dark:from-slate-900 dark:via-primary-900 dark:to-slate-900 pt-16 sm:pt-20 md:pt-24 lg:pt-28 py-16 sm:py-20 lg:py-24 -mt-1"
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
            {{-- Breadcrumb --}}
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

    {{-- Content Section --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            {{-- Main Content --}}
            <div class="lg:col-span-2">
                @if($service->body)
                    <div class="prose prose-lg max-w-none leading-relaxed service-content transition-colors duration-300" :class="darkMode ? 'prose-invert' : ''">
                        {!! $service->body !!}
                    </div>
                    
                    <style>
                        .service-content { color: inherit; }
                        .service-content p { margin-bottom: 1.5rem; line-height: 1.8; }
                        .service-content h2 { 
                            margin-top: 2.5rem; 
                            margin-bottom: 1rem; 
                            font-weight: 700; 
                            font-size: 1.5rem;
                            padding-bottom: 0.5rem;
                            border-bottom: 2px solid #e5e7eb;
                        }
                        .dark .service-content h2 { border-color: #374151; }
                        .service-content h3 { 
                            margin-top: 1.5rem; 
                            margin-bottom: 0.75rem; 
                            font-weight: 600; 
                            font-size: 1.25rem; 
                        }
                        .service-content ul, .service-content ol { 
                            margin-bottom: 1.5rem; 
                            padding-left: 1.5rem; 
                        }
                        .service-content li { 
                            margin-bottom: 0.75rem; 
                            line-height: 1.7;
                        }
                        .service-content li::marker { color: #2563eb; }
                        .dark .service-content li::marker { color: #7c3aed; }
                        .service-content img { 
                            border-radius: 0.75rem; 
                            margin-top: 2rem; 
                            margin-bottom: 2rem;
                            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                        }
                        .service-content blockquote { 
                            margin: 2rem 0; 
                            padding: 1.25rem 1.5rem; 
                            border-left: 4px solid #2563eb; 
                            background: rgba(37, 99, 235, 0.05); 
                            border-radius: 0 0.5rem 0.5rem 0;
                            font-style: italic;
                        }
                        .dark .service-content blockquote { 
                            border-color: #7C3AED; 
                            background-color: rgba(124, 58, 237, 0.08); 
                        }
                        .service-content table {
                            border-collapse: collapse;
                            width: 100%;
                            margin-bottom: 1.5rem;
                            border-radius: 0.5rem;
                            overflow: hidden;
                        }
                        .service-content th, .service-content td {
                            border: 1px solid #e5e7eb;
                            padding: 0.875rem 1rem;
                            text-align: left;
                        }
                        .dark .service-content th, .dark .service-content td { border-color: #374151; }
                        .service-content th {
                            background-color: #f9fafb;
                            font-weight: 600;
                            color: #374151;
                        }
                        .dark .service-content th {
                            background-color: #1f2937;
                            color: #e5e7eb;
                        }
                        .service-content tr:nth-child(even) { background-color: #f9fafb; }
                        .dark .service-content tr:nth-child(even) { background-color: rgba(31, 41, 55, 0.5); }
                        .dark .service-content { color: rgb(229, 231, 235); }
                        .dark .service-content h2, .dark .service-content h3 { color: rgb(243, 244, 246); }
                        .dark .service-content strong { color: rgb(243, 244, 246); }
                        .service-content a { color: #2563eb; text-decoration: underline; text-underline-offset: 2px; }
                        .dark .service-content a { color: #60a5fa; }
                        .service-content code {
                            background: #f3f4f6;
                            padding: 0.2rem 0.4rem;
                            border-radius: 0.25rem;
                            font-size: 0.875em;
                            color: #dc2626;
                        }
                        .dark .service-content code { background: #374151; color: #f87171; }
                        .service-content pre {
                            background: #1f2937;
                            color: #e5e7eb;
                            padding: 1.25rem;
                            border-radius: 0.5rem;
                            overflow-x: auto;
                            margin-bottom: 1.5rem;
                        }
                    </style>
                @endif

                {{-- Published Books Reference --}}
                @if($publishedBooks && $publishedBooks->count() > 0)
                <div class="mt-8 pt-5 border-t transition-colors duration-300" :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                    <h3 class="text-xs font-bold uppercase tracking-wider mb-3 transition-colors" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">📚 Contoh Buku Terbitan Kami</h3>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($publishedBooks as $book)
                        <a href="{{ route('books.show', $book->slug) }}" class="group block rounded-lg border overflow-hidden transition-all duration-300 hover:shadow-sm" :class="darkMode ? 'border-slate-700 hover:border-slate-600 bg-slate-800/30' : 'border-gray-200 hover:border-gray-300 bg-white'">
                            @if($book->cover_url)
                                <div class="aspect-[2/3] overflow-hidden">
                                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                </div>
                            @else
                                <div class="aspect-[2/3] flex items-center justify-center transition-colors" :class="darkMode ? 'bg-slate-700' : 'bg-gray-100'">
                                    <span class="text-lg">📖</span>
                                </div>
                            @endif
                            <div class="p-2">
                                <h4 class="font-medium text-[10px] leading-tight line-clamp-2 transition-colors group-hover:text-primary-600 dark:group-hover:text-primary-400" :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $book->title }}</h4>
                                <p class="text-[9px] mt-0.5 transition-colors" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">{{ $book->author }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="lg:col-span-1">
                <div class="lg:sticky lg:top-28 space-y-6">
                    {{-- Pricing Card --}}
                    <div class="p-6 rounded-2xl border transition-colors duration-300" :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-gray-200 shadow-sm'">
                        <h3 class="text-lg font-bold mb-4 transition-colors" :class="darkMode ? 'text-white' : 'text-gray-900'">Paket Layanan</h3>
                        
                        @php
                            $plans = $service->plans ?? collect();
                        @endphp
                        
                        @if($plans->count() > 0)
                            <div class="space-y-4">
                                @foreach($plans as $plan)
                                <div class="p-4 rounded-xl border transition-all duration-300 {{ $plan->is_popular ? 'border-primary-500 bg-primary-50/50 dark:bg-primary-900/20' : 'border-gray-200 dark:border-slate-700' }}">
                                    @if($plan->is_popular)
                                        <span class="inline-block text-[10px] font-bold uppercase tracking-wider text-primary-600 dark:text-primary-400 mb-2">⭐ Rekomendasi</span>
                                    @endif
                                    <h4 class="font-semibold text-sm mb-1 transition-colors" :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $plan->name }}</h4>
                                    <div class="flex items-baseline gap-1 mb-2">
                                        <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                                        @if($plan->subtitle)
                                            <span class="text-xs text-gray-500 dark:text-gray-400">/ {{ $plan->subtitle }}</span>
                                        @endif
                                    </div>
                                    <!-- Description removed as it's not in the schema -->
                                    @if($plan->features && is_array($plan->features) && count($plan->features) > 0)
                                        <ul class="space-y-1.5 mb-4">
                                            @foreach($plan->features as $feature)
                                                @if($feature)
                                                <li class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                                                    <svg class="w-3.5 h-3.5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                    {{ $feature }}
                                                </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                    <a href="{{ route('contact') }}?service={{ $service->slug }}&plan={{ $plan->id }}" 
                                       class="block w-full text-center py-2.5 rounded-lg text-xs font-semibold transition-all {{ $plan->is_recommended ? 'bg-primary-600 text-white hover:bg-primary-700' : 'border-2 border-primary-200 dark:border-primary-800 text-primary-700 dark:text-primary-300 hover:bg-primary-50 dark:hover:bg-primary-900/20' }}">
                                        Pilih Paket
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Hubungi kami untuk informasi harga</p>
                                <a href="{{ route('contact') }}?service={{ $service->slug }}" 
                                   class="block w-full text-center py-2.5 rounded-lg bg-primary-600 text-white text-xs font-semibold hover:bg-primary-700 transition">
                                    Konsultasi Gratis
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- CTA Card --}}
                    <div class="p-6 rounded-2xl bg-gradient-to-br from-primary-600 to-blue-700 text-white">
                        <h3 class="text-lg font-bold mb-2">Butuh Bantuan?</h3>
                        <p class="text-sm text-primary-100 mb-4">Tim kami siap membantu mewujudkan proyek Anda.</p>
                        <a href="{{ route('contact') }}" class="block w-full text-center py-2.5 rounded-lg bg-white text-primary-600 text-xs font-bold hover:bg-primary-50 transition">
                            Hubungi Kami
                        </a>
                    </div>

                    {{-- Other Services --}}
                    @if($otherServices->count() > 0)
                    <div class="p-6 rounded-2xl border transition-colors duration-300" :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-gray-200 shadow-sm'">
                        <h3 class="text-sm font-bold uppercase tracking-wider mb-4 transition-colors" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Layanan Lainnya</h3>
                        <div class="space-y-3">
                            @foreach($otherServices as $other)
                            <a href="{{ route('services.show', $other->slug) }}" class="flex items-center gap-3 p-3 rounded-xl transition-all hover:bg-gray-50 dark:hover:bg-slate-700 group">
                                <div class="w-10 h-10 rounded-lg bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center flex-shrink-0">
                                    @if(preg_match('/^[a-z-]+$/i', $other->icon))
                                        <ion-icon name="{{ $other->icon }}" class="text-lg text-primary-600 dark:text-primary-400"></ion-icon>
                                    @else
                                        <span class="text-lg">{{ $other->icon }}</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium transition-colors group-hover:text-primary-600 dark:group-hover:text-primary-400" :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $other->title }}</p>
                                    @if($other->excerpt)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Str::limit($other->excerpt, 40) }}</p>
                                    @endif
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </aside>
        </div>

        {{-- Bottom CTA --}}
        <div class="mt-12 p-8 rounded-2xl bg-gradient-to-br from-primary-50 to-blue-50 dark:from-slate-800 dark:to-slate-700 border border-primary-100 dark:border-slate-600">
            <div class="flex flex-col sm:flex-row items-center gap-6">
                <div class="flex-1 text-center sm:text-left">
                    <h3 class="text-xl font-bold mb-2 transition-colors" :class="darkMode ? 'text-white' : 'text-gray-900'">Tertarik dengan Layanan Ini?</h3>
                    <p class="text-sm transition-colors" :class="darkMode ? 'text-gray-300' : 'text-gray-600'">Konsultasikan kebutuhan Anda dengan tim kami. Kami siap membantu mewujudkan proyek Anda.</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('contact') }}?service={{ $service->slug }}" class="cta-center inline-flex gap-2 bg-primary-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-700 transition shadow-lg shadow-primary-500/20" style="display:inline-flex;align-items:center;justify-content:center;">
                        <ion-icon name="chatbubble-ellipses-outline"></ion-icon> Konsultasi Sekarang
                    </a>
                    <a href="{{ route('services.index') }}" class="cta-center inline-flex gap-2 border-2 border-primary-200 dark:border-slate-600 text-primary-700 dark:text-primary-300 px-6 py-3 rounded-xl font-semibold hover:bg-primary-50 dark:hover:bg-slate-700 transition" style="display:inline-flex;align-items:center;justify-content:center;">
                        <ion-icon name="arrow-back-outline"></ion-icon> Layanan Lainnya
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


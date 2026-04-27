<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://unpkg.com https://*.unpkg.com https://*.jsdelivr.net; connect-src 'self' ws://127.0.0.1:5173 http://127.0.0.1:5173 ws://localhost:5173 http://localhost:5173 https://unpkg.com https://*.unpkg.com https://*.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://unpkg.com; font-src 'self' https://fonts.gstatic.com https://unpkg.com; img-src 'self' data: https: blob:; media-src 'self' https:">

    <title>@yield('title', $siteSettings->get('site_name', 'Yayasan')) - {{ $siteSettings->get('site_tagline', '') }}
    </title>
    <meta name="description" content="@yield('meta_description', $siteSettings->get('site_description', ''))">
    <meta name="robots" content="index, follow">

    <!-- Google Scholar Meta Tags -->
    @yield('meta_scholar')
    @if(isset($post) || isset($book))
        @if(isset($post))
            <meta name="citation_title" content="{{ $post->title }}">
            <meta name="citation_publication_date" content="{{ $post->published_at?->format('Y/m/d') }}">
            <meta name="citation_author"
                content="{{ $siteSettings->get('site_author', $siteSettings->get('site_name', 'Binakarya Cendekia')) }}">
            <meta name="citation_journal_title" content="{{ $siteSettings->get('site_name', 'Binakarya Cendekia') }}">
        @endif
        @if(isset($book))
            <meta name="citation_title" content="{{ $book->title }}">
            <meta name="citation_author" content="{{ $book->author }}">
            <meta name="citation_publication_date" content="{{ $book->published_year ?? now()->format('Y') }}">
            <meta name="citation_isbn" content="{{ $book->isbn ?? '' }}">
            @if($book->doi) <meta name="citation_doi" content="{{ $book->doi }}"> @endif
            @if($book->keywords) <meta name="citation_keywords" content="{{ $book->keywords }}"> @endif
            <meta name="citation_abstract_html_url" content="{{ route('books.show', $book->slug) }}">
            @if($book->pdf_url) <meta name="citation_pdf_url" content="{{ $book->pdf_url }}"> @endif
        @endif
    @endif

    @yield('schema')

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('title', $siteSettings->get('site_name', 'Yayasan'))">
    <meta property="og:description" content="@yield('meta_description', $siteSettings->get('site_description', ''))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @hasSection('og_image')
        <meta property="og:image" content="@yield('og_image')">
    @endif

    @if($siteSettings->get('logo'))
        <link rel="icon" href="{{ asset('storage/' . $siteSettings->get('logo')) }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    @yield('styles')
    <style>
        ion-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            vertical-align: middle;
            line-height: 1;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Logo letter spacing */
        .brand-logo {
            /* letter-spacing: 0.08em; */
        }

        /* CTA Center — force horizontal & vertical center alignment */
        .cta-center {
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
        }
        /* Full-width variant */
        .cta-center.w-full {
            display: flex;
            align-items: center !important;
            justify-content: center !important;
        }

        /* Prevent horizontal overflow on mobile */
        html,
        body {
            overflow-x: hidden;
            max-width: 100vw;
        }

        /* Safe area padding for notched phones */
        @supports (padding: env(safe-area-inset-bottom)) {
            .safe-bottom {
                padding-bottom: env(safe-area-inset-bottom);
            }
        }
    </style>
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body
    class="font-sans antialiased transition-colors duration-500 bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-100"
    x-data="{ 
        mobileMenuOpen: false, 
        isScrolled: false,
        darkMode: localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        layananOpen: false
      }" x-init="
        $watch('darkMode', val => {
          localStorage.setItem('darkMode', val);
          document.documentElement.classList.toggle('dark', val);
        });
        document.documentElement.classList.toggle('dark', darkMode);
      " @scroll.window="isScrolled = (window.scrollY > 20)" x-cloak>

    {{-- Global Skeleton Loader --}}
    <div id="page-loader"
        class="fixed inset-0 z-[99999] bg-white dark:bg-slate-800 flex flex-col items-center justify-center transition-opacity duration-500 ease-out">
        <div class="animate-pulse flex flex-col items-center">
            <div class="h-6 w-36 sm:h-8 sm:w-48 bg-gray-200 dark:bg-gray-700 rounded mb-6 sm:mb-8"></div>
            <div class="flex gap-3 sm:gap-4 mb-10 sm:mb-12">
                <div class="h-2.5 w-12 sm:h-3 sm:w-16 bg-gray-100 dark:bg-gray-600 rounded"></div>
                <div class="h-2.5 w-12 sm:h-3 sm:w-16 bg-gray-100 dark:bg-gray-600 rounded"></div>
                <div class="h-2.5 w-12 sm:h-3 sm:w-16 bg-gray-100 dark:bg-gray-600 rounded"></div>
            </div>
            <div class="h-1 w-10 sm:w-12 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            window.addEventListener('load', function () {
                const loader = document.getElementById('page-loader');
                if (loader) {
                    setTimeout(() => {
                        loader.style.opacity = '0';
                        setTimeout(() => loader.remove(), 500);
                    }, 300);
                }
            });
        });
    </script>

    {{-- Floating WhatsApp Icon --}}
    @if($siteSettings->get('wa_number'))
        <a href="https://wa.me/{{ $siteSettings->get('wa_number') }}" target="_blank" x-cloak x-show="isScrolled"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-8"
            class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-[100] w-12 h-12 sm:w-14 sm:h-14 bg-green-500 rounded-full shadow-lg shadow-green-500/30 flex items-center justify-center text-white hover:bg-green-600 hover:shadow-xl hover:scale-105 transition-all duration-300 safe-bottom">
            <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
            </svg>
        </a>
    @endif

    {{-- Header / Navbar - Floating on Scroll --}}
    <header class="fixed z-[60] transition-all duration-500"
        :class="isScrolled 
                ? 'top-4 left-4 right-4 rounded-full' 
                : 'top-0 left-0 right-0 rounded-none'"
        :style="isScrolled ? 'max-width: calc(100% - 2rem)' : 'max-width: 100%'"
        style="will-change: transform, background-color, border-radius;">

        <nav class="flex items-center justify-between transition-all duration-500 relative px-4 sm:px-6 lg:px-8"
            :class="isScrolled 
                ? (darkMode ? 'bg-slate-900/98 shadow-2xl py-2.5 sm:py-3 backdrop-blur-md' : 'bg-gray-50/98 shadow-2xl py-2.5 sm:py-3 backdrop-blur-md')
                : (darkMode ? 'bg-gradient-to-r from-slate-950 to-slate-900 border-slate-700 shadow-2xl py-3 sm:py-3' : 'bg-white border-gray-300 shadow-md py-3 sm:py-3')"
            style="will-change: background-color, background-image;">

            <div class="max-w-6xl mx-auto px-0 w-full flex items-center justify-between">

                {{-- Left: Logo (Always Visible but smaller) --}}
                <div class="flex items-center transition-all duration-500 origin-left flex-shrink-0">
                <a href="{{ route('homepage') }}"
                    class="flex items-center flex-shrink-0 whitespace-nowrap transition-transform" title="Beranda">
                    <span class="font-serif font-bold transition-all duration-500 tracking-wide brand-logo flex gap-1"
                        :class="darkMode ? 'text-white' : 'text-gray-900'"
                        :style="isScrolled ? 'font-size: 0.75rem' : 'font-size: 1.1rem'">
                        <span :class="darkMode ? 'text-white' : 'text-black'">Bina Karya</span>
                        <span class="text-primary-600">Cendekia</span>
                    </span>
                </a>
                </div>

                {{-- Center: Navigation (Centered) --}}
                <div class="hidden lg:flex items-center gap-1 flex-1 justify-center px-4">
                @php
                    $sortedMenus = $publicMenus->sortBy(function($menu) {
                        return strtolower(trim($menu->label)) === 'beranda' ? -1 : $menu->order;
                    });
                @endphp
                @forelse($sortedMenus as $menu)
                    @if($menu->children->count() > 0)
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" 
                               class="flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 whitespace-nowrap"
                               :class="open 
                                  ? (darkMode ? 'text-primary-300 bg-slate-700/80' : 'text-primary-600 bg-blue-100') 
                                  : (isScrolled ? (darkMode ? 'text-gray-50 hover:text-primary-300 hover:bg-slate-700/50' : 'text-gray-800 hover:text-primary-600 hover:bg-blue-50') 
                                               : (darkMode ? 'text-gray-50 hover:text-primary-300 hover:bg-slate-700/50' : 'text-gray-800 hover:text-primary-600 hover:bg-blue-50'))">
                                {{ $menu->label }}
                                <svg class="w-3.5 h-3.5 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            {{-- Clean Dropdown --}}
                                @php
                                    $defaultIcons = [
                                        'pelatihan akademik' => ['icon' => 'school-outline'],
                                        'pelatihan' => ['icon' => 'ribbon-outline'],
                                        'penerbitan buku' => ['icon' => 'library-outline'],
                                        'publikasi buku' => ['icon' => 'book-outline'],
                                        'buku' => ['icon' => 'book-outline'],
                                        'katalog' => ['icon' => 'grid-outline'],
                                        'layanan' => ['icon' => 'briefcase-outline'],
                                        'jurnal' => ['icon' => 'document-text-outline'],
                                        'blog' => ['icon' => 'newspaper-outline'],
                                        'tentang' => ['icon' => 'information-circle-outline'],
                                        'kontak' => ['icon' => 'call-outline'],
                                        'beranda' => ['icon' => 'home-outline'],
                                    ];
                                    $themeBg = 'bg-blue-50';
                                    $themeDarkBg = 'dark:bg-blue-900/30';
                                    $themeText = 'text-blue-600';
                                    $themeDarkText = 'dark:text-blue-400';
                                @endphp
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute left-0 mt-2 w-72 sm:w-80 rounded-2xl shadow-2xl border overflow-hidden z-50 p-2"
                                 :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-gray-200'">
                                @foreach($menu->children as $child)
                                    @php
                                        $childLabel = strtolower(trim($child->label));
                                        $iconConfig = $defaultIcons[$childLabel] ?? null;
                                        $iconName = $child->icon ?? ($iconConfig['icon'] ?? 'cube-outline');
                                        $iconBg = $themeBg;
                                        $iconDarkBg = $themeDarkBg;
                                        $iconText = $themeText;
                                        $iconDarkText = $themeDarkText;
                                    @endphp
                                    <a href="{{ $child->is_external ? $child->url : url($child->url) }}" 
                                       @if($child->target == '_blank') target="_blank" @endif
                                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all border"
                                       :class="darkMode ? 'text-gray-50 border-transparent hover:bg-slate-700 hover:border-slate-600' : 'text-gray-800 border-transparent hover:bg-gray-50 hover:border-gray-200'">
                                        <div class="w-9 h-9 rounded-xl {{ $iconBg }} {{ $iconDarkBg }} flex items-center justify-center flex-shrink-0">
                                            @if($child->thumbnail)
                                                <img src="{{ asset('storage/' . $child->thumbnail) }}" alt="" class="w-5 h-5 object-contain">
                                            @else
                                                <ion-icon name="{{ $iconName }}" class="text-base {{ $iconText }} {{ $iconDarkText }}"></ion-icon>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0 overflow-hidden">
                                            <div class="font-semibold truncate leading-tight flex items-center gap-1.5">
                                                {{ $child->label }}
                                            </div>
                                            @if($child->subtitle)
                                            <div class="text-xs truncate mt-0.5"
                                                :class="darkMode ? 'text-gray-400' : 'text-gray-500'">
                                                {{ $child->subtitle }}
                                            </div>
                                            @else
                                                @php
                                                    $subtitleMap = [
                                                        'pelatihan akademik' => 'visioncenter.id',
                                                        'publikasi buku' => 'Koleksi terbitan kami',
                                                        'buku' => 'Koleksi terbitan kami',
                                                        'jurnal' => '',
                                                    ];
                                                @endphp
                                                @if(isset($subtitleMap[$childLabel]))
                                                <div class="text-xs truncate mt-0.5"
                                                    :class="darkMode ? 'text-gray-400' : 'text-gray-500'">
                                                    {{ $subtitleMap[$childLabel] }}
                                                </div>
                                                @endif
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $menu->is_external ? $menu->url : url($menu->url) }}" 
                           @if($menu->target == '_blank') target="_blank" @endif
                           class="px-4 py-2.5 text-sm font-semibold rounded-lg transition-all duration-300 relative group overflow-hidden whitespace-nowrap"
                           :class="isScrolled 
                              ? (darkMode ? 'text-gray-50 hover:text-primary-300 hover:bg-slate-700/50' : 'text-gray-800 hover:text-primary-600 hover:bg-blue-50') 
                              : (darkMode ? 'text-gray-50 hover:text-primary-300 hover:bg-slate-700/50' : 'text-gray-800 hover:text-primary-600 hover:bg-blue-50')">
                            {{ $menu->label }}
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-600 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                    @endif
                @empty
                    {{-- Fallback navigation if database is empty --}}
                    <a href="{{ route('homepage') }}" class="px-4 py-2.5 text-sm font-semibold rounded-lg transition-all hover:bg-blue-50 dark:hover:bg-slate-700/50 whitespace-nowrap">Beranda</a>
                    <a href="{{ route('about') }}" class="px-4 py-2.5 text-sm font-semibold rounded-lg transition-all hover:bg-blue-50 dark:hover:bg-slate-700/50 whitespace-nowrap">Tentang</a>
                    <a href="{{ route('books.index') }}" class="px-4 py-2.5 text-sm font-semibold rounded-lg transition-all hover:bg-blue-50 dark:hover:bg-slate-700/50 whitespace-nowrap">Buku</a>
                    <a href="{{ route('blog.index') }}" class="px-4 py-2.5 text-sm font-semibold rounded-lg transition-all hover:bg-blue-50 dark:hover:bg-slate-700/50 whitespace-nowrap">Blog</a>
                    <a href="{{ route('contact') }}" class="px-4 py-2.5 text-sm font-semibold rounded-lg transition-all hover:bg-blue-50 dark:hover:bg-slate-700/50 whitespace-nowrap">Kontak</a>
                @endforelse
                </div>

                {{-- Right: Actions (Always Visible) --}}
                <div class="flex items-center justify-end gap-2.5 sm:gap-3 transition-all duration-500 origin-right ml-auto">

                @php
                    $consultUrl = $siteSettings->get('wa_number')
                        ? 'https://wa.me/' . $siteSettings->get('wa_number') . '?text=Halo,%20saya%20ingin%20konsultasi%20gratis'
                        : route('contact');
                @endphp

                {{-- Hamburger --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="order-1 lg:hidden w-9 h-9 sm:w-8 sm:h-8 rounded-lg transition-all duration-300 border flex items-center justify-center shadow-sm hover:scale-105 active:scale-95"
                        :class="darkMode ? 'text-white bg-slate-700 border-slate-600' : 'text-blue-900 bg-gray-100 border-gray-200'"
                        title="Menu">
                    <div class="flex flex-col justify-center items-center w-4 h-4 gap-[3px]">
                        <span class="block w-4 h-0.5 bg-current transition-all duration-300 origin-center" :class="mobileMenuOpen ? 'rotate-45 translate-y-[7px]' : ''"></span>
                        <span class="block w-4 h-0.5 bg-current transition-all duration-300" :class="mobileMenuOpen ? 'opacity-0 scale-x-0' : ''"></span>
                        <span class="block w-4 h-0.5 bg-current transition-all duration-300 origin-center" :class="mobileMenuOpen ? '-rotate-45 -translate-y-[7px]' : ''"></span>
                    </div>
                </button>

                {{-- CTA Button Removed for redundancy --}}


                {{-- Dark Mode Toggle --}}
                <button @click="darkMode = !darkMode" 
                        class="order-3 relative w-9 h-9 sm:w-8 sm:h-8 rounded-lg transition-all duration-300 border flex items-center justify-center shadow-sm hover:scale-105 active:scale-95"
                        :class="darkMode ? 'bg-slate-700 border-slate-600 text-yellow-300' : 'bg-gray-100 border-gray-200 text-amber-500'"
                        title="Alihkan tema">
                    <svg x-show="!darkMode" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                    </svg>
                    <svg x-show="darkMode" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                </button>

                {{-- CTA Button --}}
                <a href="{{ $consultUrl }}" target="_blank" rel="noopener"
                   class="order-4 flex items-center gap-1.5 px-3 sm:px-4 py-2 sm:py-1.5 bg-primary-600 hover:bg-primary-700 text-white text-[10px] sm:text-xs font-bold rounded-lg transition-all shadow-md hover:shadow-lg active:scale-95 whitespace-nowrap"
                   title="Get Started">
                    Get Started
                </a>
                </div>
            </div>
        </nav>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-0" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-0"
            class="lg:hidden block fixed top-auto left-0 right-0 border-t shadow-2xl max-h-[calc(100vh-56px)] overflow-y-auto z-50"
            :class="darkMode ? 'bg-slate-900 border-slate-800 text-slate-100' : 'bg-white border-gray-200 text-slate-800'"
            @click.away="mobileMenuOpen = false">
            <div class="max-w-7xl mx-auto px-4 sm:px-5 py-3 sm:py-4 space-y-2">
                <div class="space-y-2 py-3 max-h-[calc(100vh-180px)] overflow-y-auto">
                    @php
                        $sortedMobileMenus = $publicMenus->sortBy(function($menu) {
                            return strtolower(trim($menu->label)) === 'beranda' ? -1 : $menu->order;
                        });
                    @endphp
                    @forelse($sortedMobileMenus as $menu)

                        @if($menu->children->count() > 0)
                            <div x-data="{ open: false }" class="border rounded-lg overflow-hidden shadow-sm" :class="darkMode ? 'border-slate-700 bg-slate-800/30' : 'border-gray-100 bg-gray-50/50'">
                                <button @click="open = !open" class="w-full flex items-center justify-between p-3 sm:p-2.5 text-xs font-semibold uppercase tracking-wider">
                                    <span :class="darkMode ? 'text-gray-100' : 'text-gray-900'" class="flex items-center gap-2">
                                        @php
                                            $mobileDefaultIcons = [
                                                'pelatihan akademik' => 'school-outline',
                                                'pelatihan' => 'ribbon-outline',
                                                'penerbitan buku' => 'library-outline',
                                                'publikasi buku' => 'book-outline',
                                                'buku' => 'book-outline',
                                                'katalog' => 'grid-outline',
                                                'layanan' => 'briefcase-outline',
                                                'jurnal' => 'document-text-outline',
                                                'blog' => 'newspaper-outline',
                                                'tentang' => 'information-circle-outline',
                                                'kontak' => 'call-outline',
                                                'beranda' => 'home-outline',
                                            ];
                                            $mMenuLabel = strtolower(trim($menu->label));
                                            $mIcon = $menu->icon ?? ($mobileDefaultIcons[$mMenuLabel] ?? 'cube-outline');
                                        @endphp
                                        <ion-icon name="{{ $mIcon }}" class="text-base"></ion-icon>
                                        {{ $menu->label }}
                                    </span>
                                    <svg class="w-3.5 h-3.5 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                @php
                                    $mobileDefaultIcons = [
                                        'pelatihan akademik' => 'school-outline',
                                        'publikasi buku' => 'book-outline',
                                        'buku' => 'book-outline',
                                        'katalog' => 'grid-outline',
                                        'layanan' => 'briefcase-outline',
                                        'jurnal' => 'document-text-outline',
                                        'blog' => 'newspaper-outline',
                                        'tentang' => 'information-circle-outline',
                                        'kontak' => 'call-outline',
                                        'beranda' => 'home-outline',
                                    ];
                                @endphp
                                <div x-show="open" x-collapse class="px-2 pb-2 space-y-1 border-t pt-2" :class="darkMode ? 'border-slate-700' : 'border-gray-100'">
                                    @foreach($menu->children as $child)
                                        @php
                                            $mChildLabel = strtolower(trim($child->label));
                                            $mIconName = $child->icon ?? ($mobileDefaultIcons[$mChildLabel] ?? 'cube-outline');
                                            $childIconBg = 'bg-blue-50';
                                            $childIconDarkBg = 'dark:bg-blue-900/30';
                                            $childIconText = 'text-primary-600';
                                            $childIconDarkText = 'dark:text-primary-400';
                                            
                                            $iconConfig = ['bg' => 'bg-blue-50', 'dark_bg' => 'dark:bg-blue-900/30', 'text' => 'text-blue-600', 'dark_text' => 'dark:text-blue-400'];
                                            $colorConfig = $iconConfig;
                                        @endphp
                                        <a href="{{ $child->is_external ? $child->url : url($child->url) }}" 
                                           @if($child->target == '_blank') target="_blank" @endif
                                           @click="mobileMenuOpen = false"
                                           class="flex items-center gap-2.5 p-2.5 sm:p-2 rounded-lg text-xs transition-all duration-300"
                                           :class="darkMode ? 'text-gray-200 hover:text-white hover:bg-slate-700/50' : 'text-gray-700 hover:text-primary-600 hover:bg-blue-50'">
                                            <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ $colorConfig['bg'] }} {{ $colorConfig['dark_bg'] }}">
                                                @if($child->thumbnail)
                                                    <img src="{{ asset('storage/' . $child->thumbnail) }}" alt="" class="w-4 h-4 object-contain">
                                                @else
                                                    <ion-icon name="{{ $mIconName }}" class="text-sm {{ $colorConfig['text'] }} {{ $colorConfig['dark_text'] }}"></ion-icon>
                                                @endif
                                            </span>
                                            <span class="truncate flex items-center gap-1">
                                                {{ $child->label }}
                                                @if($child->is_external || $child->target == '_blank')
                                                    <ion-icon name="open-outline" class="text-[10px] opacity-50 flex-shrink-0"></ion-icon>
                                                @endif
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ $menu->is_external ? $menu->url : url($menu->url) }}" 
                               @if($menu->target == '_blank') target="_blank" @endif
                               @click="mobileMenuOpen = false"
                               class="flex items-center gap-2.5 p-3 sm:p-2.5 rounded-lg text-xs font-semibold uppercase tracking-wider transition-all"
                               :class="darkMode ? 'text-gray-100 hover:bg-slate-700 hover:text-primary-400' : 'text-gray-800 hover:bg-blue-50 hover:text-primary-600'">
                                @php
                                    $mMenuLabel = strtolower(trim($menu->label));
                                    $mIcon = $menu->icon ?? ($mobileDefaultIcons[$mMenuLabel] ?? 'cube-outline');
                                @endphp
                                <ion-icon name="{{ $mIcon }}" class="text-base"></ion-icon>
                                {{ $menu->label }}
                            </a>
                        @endif
                    @empty
                        {{-- Fallback navigation --}}
                        <a href="{{ route('homepage') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 p-3 sm:p-2.5 rounded-lg text-xs font-semibold uppercase tracking-wider" :class="darkMode ? 'text-gray-100 hover:bg-slate-700' : 'text-gray-800 hover:bg-blue-50'">
                            <ion-icon name="home-outline" class="text-base"></ion-icon>
                            Beranda
                        </a>
                        <a href="{{ route('about') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 p-3 sm:p-2.5 rounded-lg text-xs font-semibold uppercase tracking-wider" :class="darkMode ? 'text-gray-100 hover:bg-slate-700' : 'text-gray-800 hover:bg-blue-50'">
                            <ion-icon name="information-circle-outline" class="text-base"></ion-icon>
                            Tentang Kami
                        </a>
                        <a href="{{ route('books.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 p-3 sm:p-2.5 rounded-lg text-xs font-semibold uppercase tracking-wider" :class="darkMode ? 'text-gray-100 hover:bg-slate-700' : 'text-gray-800 hover:bg-blue-50'">
                            <ion-icon name="book-outline" class="text-base"></ion-icon>
                            Katalog Buku
                        </a>
                        <a href="{{ route('blog.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 p-3 sm:p-2.5 rounded-lg text-xs font-semibold uppercase tracking-wider" :class="darkMode ? 'text-gray-100 hover:bg-slate-700' : 'text-gray-800 hover:bg-blue-50'">
                            <ion-icon name="newspaper-outline" class="text-base"></ion-icon>
                            Blog Terbaru
                        </a>
                        <a href="{{ route('contact') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 p-3 sm:p-2.5 rounded-lg text-xs font-semibold uppercase tracking-wider" :class="darkMode ? 'text-gray-100 hover:bg-slate-700' : 'text-gray-800 hover:bg-blue-50'">
                            <ion-icon name="call-outline" class="text-base"></ion-icon>
                            Kontak Kami
                        </a>
                    @endforelse
                </div>

                {{-- Mobile WhatsApp CTA --}}
                <div class="pt-3 border-t space-y-3 mt-3" :class="darkMode ? 'border-slate-800' : 'border-gray-100'">
                    @if($siteSettings->get('wa_number'))
                        <a href="https://wa.me/{{ $siteSettings->get('wa_number') }}" target="_blank" rel="noopener"
                            class="cta-center flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold uppercase tracking-wider px-4 py-3 rounded-lg shadow-lg shadow-green-600/20 active:scale-95 transition-all w-full"
                            style="display:flex;align-items:center;justify-content:center;">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                            WhatsApp
                        </a>
                    @endif

                    <div class="flex items-center justify-between px-2 py-2 rounded-lg" :class="darkMode ? 'bg-slate-800/30' : 'bg-gray-50'">
                        <span class="text-xs font-semibold" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">Mode Gelap</span>
                        <button @click="darkMode = !darkMode" 
                                class="w-10 h-5 rounded-full transition-all duration-300 relative flex-shrink-0"
                                :class="darkMode ? 'bg-primary-600' : 'bg-gray-300'">
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full transition-transform duration-300 flex items-center justify-center shadow-sm"
                                :class="darkMode ? 'translate-x-5 bg-white text-primary-600' : 'translate-x-0 bg-white text-gray-400'">
                                <ion-icon :name="darkMode ? 'moon' : 'sunny'" class="text-[10px]"></ion-icon>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            </div>
    </header>

    <main class="relative min-h-screen w-full overflow-x-hidden pt-0">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t transition-colors duration-300 bg-gradient-to-b"
        :class="darkMode ? 'from-slate-900 to-slate-950 border-slate-800' : 'from-blue-50/60 to-white border-gray-100'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20">
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-10 lg:gap-12 mb-10 sm:mb-14">
                {{-- Column 1: About --}}
                <div class="col-span-2 sm:col-span-2 lg:col-span-1">
                    <h3 class="font-serif font-bold text-base sm:text-lg mb-3 sm:mb-4 transition-colors brand-logo"
                        :class="darkMode ? 'text-white' : 'text-blue-900'">Bina Karya <span
                            class="text-primary-600">Cendekia</span></h3>
                    <p class="text-xs sm:text-sm leading-relaxed mb-4 max-w-sm transition-colors"
                        :class="darkMode ? 'text-gray-400' : 'text-gray-500'">
                        Lembaga penerbitan dan pengembangan ilmu pengetahuan yang berdedikasi untuk memajukan literasi
                        dan penelitian di Indonesia.
                    </p>
                    <div class="flex gap-2.5">
                        @if($siteSettings->get('facebook'))
                            <a href="{{ $siteSettings->get('facebook') }}" target="_blank"
                                class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-all duration-300 group shadow-sm border"
                                :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-blue-50 border-blue-100'">
                                <svg class="w-3.5 h-3.5 group-hover:text-white transition-colors"
                                    :class="darkMode ? 'text-gray-400' : 'text-primary-600'" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </a>
                        @endif
                        @if($siteSettings->get('instagram'))
                            <a href="{{ $siteSettings->get('instagram') }}" target="_blank"
                                class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-all duration-300 group shadow-sm border"
                                :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-blue-50 border-blue-100'">
                                <svg class="w-3.5 h-3.5 group-hover:text-white transition-colors"
                                    :class="darkMode ? 'text-gray-400' : 'text-primary-600'" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                                </svg>
                            </a>
                        @endif
                        @if($siteSettings->get('tiktok'))
                            <a href="{{ $siteSettings->get('tiktok') }}" target="_blank"
                                class="w-9 h-9 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-all duration-300 group shadow-sm border"
                                :class="darkMode ? 'bg-slate-700 border-slate-600' : 'bg-blue-50 border-blue-100'">
                                <svg class="w-4 h-4 group-hover:text-white transition-colors"
                                    :class="darkMode ? 'text-gray-400' : 'text-primary-600'" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1v-3.5a6.37 6.37 0 00-.79-.05A6.34 6.34 0 003.15 15.2a6.34 6.34 0 0010.86 4.48 6.3 6.3 0 001.88-4.48V8.75a8.26 8.26 0 004.7 1.46V6.77a4.84 4.84 0 01-1-.08z" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Column 2: Navigation --}}
                <div>
                    <h4 class="font-semibold mb-3 sm:mb-4 text-xs sm:text-sm uppercase tracking-wider transition-colors"
                        :class="darkMode ? 'text-white' : 'text-blue-900'">Navigasi</h4>
                    <ul class="space-y-1.5 sm:space-y-2 text-xs sm:text-sm">
                        <li><a href="{{ route('homepage') }}" class="transition-colors"
                                :class="darkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-500 hover:text-primary-600'">Beranda</a>
                        </li>
                        <li><a href="{{ route('about') }}" class="transition-colors"
                                :class="darkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-500 hover:text-primary-600'">Tentang
                                Kami</a></li>
                        <li><a href="{{ route('services.index') }}" class="transition-colors"
                                :class="darkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-500 hover:text-primary-600'">Layanan</a>
                        </li>
                        <li><a href="{{ route('books.index') }}" class="transition-colors"
                                :class="darkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-500 hover:text-primary-600'">Katalog
                                Buku</a></li>
                    </ul>
                </div>

                {{-- Column 3: Link Lainnya --}}
                <div>
                    <h4 class="font-semibold mb-3 sm:mb-4 text-xs sm:text-sm uppercase tracking-wider transition-colors"
                        :class="darkMode ? 'text-white' : 'text-blue-900'">Link Lainnya</h4>
                    <ul class="space-y-1.5 sm:space-y-2 text-xs sm:text-sm">
                        <li><a href="https://visioncenter.id/" target="_blank" class="transition-colors"
                                :class="darkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-500 hover:text-primary-600'">Pelatihan</a>
                        </li>
                        <li><a href="{{ route('blog.index') }}" class="transition-colors"
                                :class="darkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-500 hover:text-primary-600'">Blog</a>
                        </li>
                        <li><a href="https://journal.binakaryacendekia.id" target="_blank" class="transition-colors"
                                :class="darkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-500 hover:text-primary-600'">Jurnal</a>
                        </li>
                    </ul>
                </div>

                {{-- Column 4: Contact --}}
                <div class="col-span-2 sm:col-span-1">
                    <h4 class="font-semibold mb-3 sm:mb-4 text-xs sm:text-sm uppercase tracking-wider transition-colors"
                        :class="darkMode ? 'text-white' : 'text-blue-900'">Kontak</h4>
                    <ul class="space-y-4 sm:space-y-5 text-xs sm:text-sm">
                        <li class="flex gap-2.5 sm:gap-3 items-start">
                            <svg class="w-4 h-4 text-primary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="leading-relaxed transition-colors"
                                :class="darkMode ? 'text-gray-400' : 'text-gray-500'">Jl. DPR V Cileunyi Kulon, Kec. Cileunyi, Kabupaten Bandung, Jawa Barat 40622</span>
                        </li>
                        @if($siteSettings->get('email'))
                            <li class="flex gap-2.5 sm:gap-3 items-center">
                                <svg class="w-4 h-4 text-primary-500 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <a href="mailto:{{ $siteSettings->get('email') }}" class="break-all transition-colors"
                                    :class="darkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-500 hover:text-primary-600'">{{ $siteSettings->get('email') }}</a>
                            </li>
                        @endif
                        @if($siteSettings->get('wa_number'))
                            <li class="flex gap-2.5 sm:gap-3 items-center">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <a href="tel:+{{ $siteSettings->get('wa_number') }}" class="transition-colors"
                                    :class="darkMode ? 'text-gray-400 hover:text-green-400' : 'text-gray-500 hover:text-green-600'">0895611314372</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Footer Bottom --}}
            <div class="pt-6 sm:pt-8 flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-4 text-xs sm:text-sm transition-colors"
                :class="darkMode ? 'border-t border-slate-800 text-gray-500' : 'border-t border-gray-100 text-gray-400'">
                <p>&copy; {{ date('Y') }} Bina Karya Cendekia. Semua hak dilindungi.</p>
                <div class="flex gap-4 sm:gap-6">
                    <a href="{{ route('privacy') }}" class="transition-colors"
                        :class="darkMode ? 'hover:text-primary-400' : 'hover:text-primary-600'">Kebijakan Privasi</a>
                    <a href="{{ route('terms') }}" class="transition-colors"
                        :class="darkMode ? 'hover:text-primary-400' : 'hover:text-primary-600'">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>

</html>

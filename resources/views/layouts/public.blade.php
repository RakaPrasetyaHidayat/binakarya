<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
        /* Navbar frosted glass scroll state */
        #main-navbar.is-scrolled {
            top: 10px;
            left: 16px;
            right: 16px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.65) !important;
            backdrop-filter: blur(18px) saturate(160%);
            -webkit-backdrop-filter: blur(18px) saturate(160%);
            border: 1px solid rgba(255, 255, 255, 0.55) !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0,0,0,0.05);
        }
        #main-navbar.is-scrolled.dark-scrolled {
            background: rgba(15, 23, 42, 0.75) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4), 0 2px 8px rgba(0,0,0,0.2);
        }
        @media (max-width: 640px) {
            #main-navbar.is-scrolled {
                left: 10px;
                right: 10px;
                border-radius: 12px;
            }
        }
    </style>
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
    <script nonce="{{ $cspNonce ?? '' }}">
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
      " @scroll.window="isScrolled = (window.scrollY > 20)">

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
    <script nonce="{{ $cspNonce ?? '' }}">
        (function () {
            const hideLoader = () => {
                const loader = document.getElementById('page-loader');
                if (!loader || loader.dataset.hidden === '1') {
                    return;
                }

                loader.dataset.hidden = '1';
                loader.style.opacity = '0';
                loader.style.pointerEvents = 'none';
                setTimeout(() => {
                    if (loader && loader.parentNode) {
                        loader.remove();
                    }
                }, 500);
            };

            // Normal fast path.
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(hideLoader, 250);
            });

            // Keep compatibility for heavier assets.
            window.addEventListener('load', () => {
                setTimeout(hideLoader, 100);
            });

            // Handle browser back/forward cache where `load` may not fire.
            window.addEventListener('pageshow', () => {
                setTimeout(hideLoader, 50);
            });

            // Hard failsafe: never keep loader forever.
            setTimeout(hideLoader, 4000);
        })();
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


    @php
        $navMenus   = $publicMenus->sortBy(fn($m) => strtolower(trim($m->label)) === 'beranda' ? -1 : $m->order);
        $consultUrl = 'https://wa.me/62895611314372?text=Halo,%20saya%20ingin%20konsultasi%20gratis';
        $ddIcons = ['pelatihan akademik'=>'school-outline','pelatihan'=>'ribbon-outline','penerbitan buku'=>'library-outline','publikasi buku'=>'book-outline','buku'=>'book-outline','katalog'=>'grid-outline','layanan'=>'briefcase-outline','jurnal'=>'document-text-outline','blog'=>'newspaper-outline','tentang'=>'information-circle-outline','kontak'=>'call-outline','beranda'=>'home-outline'];
        $ddSubs  = ['pelatihan akademik'=>'visioncenter.id','pelatihan'=>'visioncenter.id','publikasi buku'=>'Koleksi terbitan kami','buku'=>'Koleksi terbitan kami','jurnal'=>'journal.binakaryacendekia.id'];
    @endphp

    <header id="main-navbar"
            class="fixed top-0 left-0 right-0 z-[9990] transition-all duration-500 ease-in-out"
            :class="isScrolled
                ? (darkMode ? 'bg-slate-900/80 border-slate-700/50' : 'bg-white/65 border-white/50')
                : (darkMode ? 'bg-slate-900 border-slate-700' : 'bg-white border-gray-200')"
            x-effect="
                const el = document.getElementById('main-navbar');
                if (isScrolled) {
                    el.classList.add('is-scrolled');
                    if (darkMode) el.classList.add('dark-scrolled'); else el.classList.remove('dark-scrolled');
                } else {
                    el.classList.remove('is-scrolled', 'dark-scrolled');
                }
            "
            style="border-bottom-width: 1px; border-style: solid;">

        {{-- DESKTOP --}}
        <div class="hidden lg:flex items-center h-16 px-6 max-w-7xl mx-auto gap-6">

            {{-- Logo --}}
            <a href="{{ route('homepage') }}" class="flex items-center gap-1 font-bold text-base flex-shrink-0">
                <span :class="isScrolled ? (darkMode ? 'text-white' : 'text-gray-900') : (darkMode ? 'text-white' : 'text-gray-900')">Bina Karya</span>
                <span class="text-blue-600">Cendekia</span>
            </a>

            {{-- Nav + Get Started (centered) --}}
            <nav class="flex items-center gap-0 flex-1 justify-center">
                @forelse($navMenus as $menu)
                    @if($menu->children->count() > 0)
                        <div class="relative" x-data="{ open:false, t:0, l:0, calc(){ const r=this.$refs.btn.getBoundingClientRect(); this.t=r.bottom+window.scrollY; this.l=r.left+window.scrollX; } }" @click.away="open=false" @keydown.escape.window="open=false">
                            <button x-ref="btn" type="button" @click.stop="if(!open){calc()} open=!open"
                                    class="flex items-center gap-1 px-4 py-2 text-sm font-medium rounded-md transition-colors"
                                    :class="open ? (isScrolled?'text-blue-600 bg-blue-50':'text-blue-600 bg-blue-50') : (isScrolled?(darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-100/60'):(darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-100'))">
                                {{ $menu->label }}
                                <svg class="w-3.5 h-3.5 flex-shrink-0 transition-transform" :class="open?'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <template x-teleport="body">
                                <div x-show="open" x-cloak
                                     x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                     :style="'position:fixed;top:'+(t+4)+'px;left:'+l+'px;min-width:220px;z-index:99999;'"
                                     class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-xl py-1.5">
                                    @foreach($menu->children as $child)
                                        @php $cl=strtolower(trim($child->label)); $ico=$child->icon??($ddIcons[$cl]??'cube-outline'); $sub=$child->subtitle??($ddSubs[$cl]??null); @endphp
                                        <a href="{{ $child->is_external?$child->url:url($child->url) }}" @if($child->target==='_blank') target="_blank" rel="noopener" @endif @click="open=false"
                                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                                            <span class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-slate-700 flex items-center justify-center flex-shrink-0">
                                                @if($child->thumbnail)<img src="{{ asset('storage/'.$child->thumbnail) }}" class="w-4 h-4 object-contain">
                                                @else<ion-icon name="{{ $ico }}" class="text-sm text-blue-600 dark:text-blue-400"></ion-icon>@endif
                                            </span>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-semibold leading-tight">{{ $child->label }}</div>
                                                @if($sub)<div class="text-xs text-gray-400 truncate mt-0.5">{{ $sub }}</div>@endif
                                            </div>
                                            @if($child->is_external||$child->target==='_blank')<ion-icon name="open-outline" class="text-xs opacity-40 flex-shrink-0"></ion-icon>@endif
                                        </a>
                                    @endforeach
                                </div>
                            </template>
                        </div>
                    @else
                        <a href="{{ $menu->is_external?$menu->url:url($menu->url) }}" @if($menu->target==='_blank') target="_blank" rel="noopener" @endif
                           class="px-4 py-2 text-sm font-medium rounded-md transition-colors"
                           :class="isScrolled?(darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-100/60'):(darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-100')">
                            {{ $menu->label }}
                        </a>
                    @endif
                @empty
                    <a href="{{ route('homepage') }}" class="px-4 py-2 text-sm font-medium rounded-md transition-colors" :class="isScrolled?(darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-100/60'):(darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-100')">Beranda</a>
                    <a href="{{ route('about') }}" class="px-4 py-2 text-sm font-medium rounded-md transition-colors" :class="isScrolled?(darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-100/60'):(darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-100')">Tentang Kami</a>
                    <a href="{{ route('services.index') }}" class="px-4 py-2 text-sm font-medium rounded-md transition-colors" :class="isScrolled?(darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-100/60'):(darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-100')">Layanan</a>
                    <a href="{{ route('books.index') }}" class="px-4 py-2 text-sm font-medium rounded-md transition-colors" :class="isScrolled?(darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-100/60'):(darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-100')">Buku</a>
                    <a href="{{ route('blog.index') }}" class="px-4 py-2 text-sm font-medium rounded-md transition-colors" :class="isScrolled?(darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-100/60'):(darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-100')">Blog</a>
                    <a href="{{ route('contact') }}" class="px-4 py-2 text-sm font-medium rounded-md transition-colors" :class="isScrolled?(darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-100/60'):(darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-100')">Kontak</a>
                @endforelse

            </nav>

            {{-- Right: dark mode toggle + Get Started --}}
            <div class="flex items-center gap-2 flex-shrink-0">
                <button @click="darkMode=!darkMode" type="button"
                        class="w-9 h-9 rounded-full border flex items-center justify-center transition-colors"
                        :class="isScrolled?'border-gray-300 bg-white/70 text-amber-500':(darkMode?'border-slate-600 bg-slate-800 text-yellow-400':'border-gray-300 bg-white text-amber-500')">
                    {{-- Sun icon (light mode) --}}
                    <svg x-show="!darkMode" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 17a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm0-12a1 1 0 0 0 1-1V3a1 1 0 1 0-2 0v1a1 1 0 0 0 1 1zm0 14a1 1 0 0 0-1 1v1a1 1 0 1 0 2 0v-1a1 1 0 0 0-1-1zm9-9h-1a1 1 0 1 0 0 2h1a1 1 0 1 0 0-2zM4 12a1 1 0 0 0-1-1H2a1 1 0 1 0 0 2h1a1 1 0 0 0 1-1zm14.95 5.54-.71-.71a1 1 0 0 0-1.41 1.41l.71.71a1 1 0 0 0 1.41-1.41zM6.46 6.46a1 1 0 0 0-1.41 0l-.71.71a1 1 0 0 0 1.41 1.41l.71-.71a1 1 0 0 0 0-1.41zm12.02.71-.71-.71a1 1 0 0 0-1.41 1.41l.71.71a1 1 0 0 0 1.41-1.41zM5.05 18.36a1 1 0 0 0 1.41 0l.71-.71a1 1 0 0 0-1.41-1.41l-.71.71a1 1 0 0 0 0 1.41z"/>
                    </svg>
                    {{-- Moon icon (dark mode) --}}
                    <svg x-show="darkMode" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z"/>
                    </svg>
                </button>
                <a href="{{ $consultUrl }}" target="_blank" rel="noopener"
                   class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors"
                   :class="isScrolled?'bg-blue-600 text-white hover:bg-blue-700':'bg-blue-600 text-white hover:bg-blue-700'">
                    Get Started
                </a>
            </div>
        </div>

        {{-- MOBILE --}}
        <div class="flex lg:hidden items-center justify-between h-16 px-4">
            <a href="{{ route('homepage') }}" class="flex items-center gap-0.5 font-bold text-sm">
                <span :class="darkMode ? 'text-white' : 'text-gray-900'">Bina Karya</span>
                <span class="text-blue-600">Cendekia</span>
            </a>
            <div class="flex items-center gap-1">
                <button @click="darkMode=!darkMode" type="button"
                        class="w-9 h-9 rounded-full border flex items-center justify-center transition-colors"
                        :class="isScrolled?'border-gray-300 bg-white/70 text-amber-500':(darkMode?'border-slate-600 bg-slate-800 text-yellow-400':'border-gray-300 bg-white text-amber-500')">
                    {{-- Sun icon (light mode) --}}
                    <svg x-show="!darkMode" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 17a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm0-12a1 1 0 0 0 1-1V3a1 1 0 1 0-2 0v1a1 1 0 0 0 1 1zm0 14a1 1 0 0 0-1 1v1a1 1 0 1 0 2 0v-1a1 1 0 0 0-1-1zm9-9h-1a1 1 0 1 0 0 2h1a1 1 0 1 0 0-2zM4 12a1 1 0 0 0-1-1H2a1 1 0 1 0 0 2h1a1 1 0 0 0 1-1zm14.95 5.54-.71-.71a1 1 0 0 0-1.41 1.41l.71.71a1 1 0 0 0 1.41-1.41zM6.46 6.46a1 1 0 0 0-1.41 0l-.71.71a1 1 0 0 0 1.41 1.41l.71-.71a1 1 0 0 0 0-1.41zm12.02.71-.71-.71a1 1 0 0 0-1.41 1.41l.71.71a1 1 0 0 0 1.41-1.41zM5.05 18.36a1 1 0 0 0 1.41 0l.71-.71a1 1 0 0 0-1.41-1.41l-.71.71a1 1 0 0 0 0 1.41z"/>
                    </svg>
                    {{-- Moon icon (dark mode) --}}
                    <svg x-show="darkMode" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z"/>
                    </svg>
                </button>
                <button @click="mobileMenuOpen=!mobileMenuOpen" type="button"
                        class="w-9 h-9 rounded-lg border flex items-center justify-center transition-colors"
                        :class="isScrolled?'border-gray-300 text-gray-700':(darkMode?'border-slate-600 text-gray-200':'border-gray-300 text-gray-700')">
                    <svg x-show="!mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Drawer --}}
        <div x-show="mobileMenuOpen" x-cloak
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden border-t shadow-lg" :class="darkMode?'bg-slate-900 border-slate-800':'bg-white border-gray-200'"
             @click.away="mobileMenuOpen=false">
            <div class="px-4 py-3 space-y-0.5 max-h-[calc(100vh-64px)] overflow-y-auto overscroll-contain">
                @forelse($navMenus as $menu)
                    @if($menu->children->count() > 0)
                        <div x-data="{open:false}">
                            <button @click="open=!open" type="button"
                                    class="w-full flex items-center justify-between px-3 py-3 rounded-xl text-sm font-medium transition-colors"
                                    :class="darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-50'">
                                <span>{{ $menu->label }}</span>
                                <svg class="w-4 h-4 transition-transform flex-shrink-0" :class="open?'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition class="pl-3 pb-1 space-y-0.5">
                                @foreach($menu->children as $child)
                                    @php $cl=strtolower(trim($child->label)); $ico=$child->icon??($ddIcons[$cl]??'cube-outline'); @endphp
                                    <a href="{{ $child->is_external?$child->url:url($child->url) }}" @if($child->target==='_blank') target="_blank" rel="noopener" @endif @click="mobileMenuOpen=false"
                                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors"
                                       :class="darkMode?'text-gray-300 hover:bg-slate-800 hover:text-white':'text-gray-600 hover:bg-blue-50 hover:text-blue-600'">
                                        <span class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                                              :class="darkMode?'bg-slate-700':'bg-blue-50'">
                                            <ion-icon name="{{ $ico }}" class="text-sm text-blue-600 dark:text-blue-400"></ion-icon>
                                        </span>
                                        {{ $child->label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $menu->is_external?$menu->url:url($menu->url) }}" @if($menu->target==='_blank') target="_blank" rel="noopener" @endif @click="mobileMenuOpen=false"
                           class="flex items-center px-3 py-3 rounded-xl text-sm font-medium transition-colors"
                           :class="darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-50'">
                            {{ $menu->label }}
                        </a>
                    @endif
                @empty
                    <a href="{{ route('homepage') }}" @click="mobileMenuOpen=false" class="flex items-center px-3 py-3 rounded-xl text-sm font-medium transition-colors" :class="darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-50'">Beranda</a>
                    <a href="{{ route('about') }}" @click="mobileMenuOpen=false" class="flex items-center px-3 py-3 rounded-xl text-sm font-medium transition-colors" :class="darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-50'">Tentang Kami</a>
                    <a href="{{ route('services.index') }}" @click="mobileMenuOpen=false" class="flex items-center px-3 py-3 rounded-xl text-sm font-medium transition-colors" :class="darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-50'">Layanan</a>
                    <a href="{{ route('books.index') }}" @click="mobileMenuOpen=false" class="flex items-center px-3 py-3 rounded-xl text-sm font-medium transition-colors" :class="darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-50'">Buku</a>
                    <a href="{{ route('blog.index') }}" @click="mobileMenuOpen=false" class="flex items-center px-3 py-3 rounded-xl text-sm font-medium transition-colors" :class="darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-50'">Blog</a>
                    <a href="{{ route('contact') }}" @click="mobileMenuOpen=false" class="flex items-center px-3 py-3 rounded-xl text-sm font-medium transition-colors" :class="darkMode?'text-white hover:bg-slate-800':'text-gray-700 hover:bg-gray-50'">Kontak</a>
                @endforelse

                {{-- CTA Button --}}
                <div class="pt-3 pb-2 border-t mt-2" :class="darkMode?'border-slate-800':'border-gray-100'">
                    <a href="{{ $consultUrl }}" target="_blank" rel="noopener"
                       class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="relative min-h-screen w-full overflow-x-hidden pt-16">
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
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
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
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
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
                                    <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1v-3.5a6.37 6.37 0 00-.79-.05A6.34 6.34 0 003.15 15.2a6.34 6.34 0 0010.86 4.48 6.3 6.3 0 001.88-4.48V8.75a8.26 8.26 0 004.7 1.46V6.77a4.84 4.84 0 01-1-.08z" />
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
                                :class="darkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-500 hover:text-primary-600'">Beranda</a></li>
                        <li><a href="{{ route('about') }}" class="transition-colors"
                                :class="darkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-500 hover:text-primary-600'">Tentang Kami</a></li>
                        <li><a href="{{ route('services.index') }}" class="transition-colors"
                                :class="darkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-500 hover:text-primary-600'">Layanan</a></li>
                        <li><a href="{{ route('books.index') }}" class="transition-colors"
                                :class="darkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-500 hover:text-primary-600'">Katalog Buku</a></li>
                    </ul>
                </div>

                {{-- Column 3: Link Lainnya --}}
                <div>
                    <h4 class="font-semibold mb-3 sm:mb-4 text-xs sm:text-sm uppercase tracking-wider transition-colors"
                        :class="darkMode ? 'text-white' : 'text-blue-900'">Link Lainnya</h4>
                    <ul class="space-y-1.5 sm:space-y-2 text-xs sm:text-sm">
                        <li><a href="https://visioncenter.id/" target="_blank" class="transition-colors"
                                :class="darkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-500 hover:text-primary-600'">Pelatihan</a></li>
                        <li><a href="{{ route('blog.index') }}" class="transition-colors"
                                :class="darkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-500 hover:text-primary-600'">Blog</a></li>
                        <li><a href="https://journal.binakaryacendekia.id" target="_blank" class="transition-colors"
                                :class="darkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-500 hover:text-primary-600'">Jurnal</a></li>
                    </ul>
                </div>

                {{-- Column 4: Contact --}}
                <div class="col-span-2 sm:col-span-1">
                    <h4 class="font-semibold mb-3 sm:mb-4 text-xs sm:text-sm uppercase tracking-wider transition-colors"
                        :class="darkMode ? 'text-white' : 'text-blue-900'">Kontak</h4>
                    <ul class="space-y-4 sm:space-y-5 text-xs sm:text-sm">
                        <li class="flex gap-2.5 sm:gap-3 items-start">
                            <svg class="w-4 h-4 text-primary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="leading-relaxed transition-colors"
                                :class="darkMode ? 'text-gray-400' : 'text-gray-500'">Jl. DPR V Cileunyi Kulon, Kec. Cileunyi, Kabupaten Bandung, Jawa Barat 40622</span>
                        </li>
                        @if($siteSettings->get('email'))
                            <li class="flex gap-2.5 sm:gap-3 items-center">
                                <svg class="w-4 h-4 text-primary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <a href="mailto:{{ $siteSettings->get('email') }}" class="break-all transition-colors"
                                    :class="darkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-500 hover:text-primary-600'">{{ $siteSettings->get('email') }}</a>
                            </li>
                        @endif
                        @if($siteSettings->get('wa_number'))
                            <li class="flex gap-2.5 sm:gap-3 items-center">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <a href="https://wa.me/62895611314372?text=Halo,%20saya%20ingin%20konsultasi%20gratis" target="_blank" rel="noopener" class="transition-colors"
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

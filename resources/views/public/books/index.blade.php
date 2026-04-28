@extends('layouts.public')

@section('title', 'Katalog Buku')
@section('meta_description', 'Katalog buku ilmiah ' . $siteSettings->get('site_name', ''))

@section('content')
<div x-data="{ darkMode: document.documentElement.classList.contains('dark') }" 
     @DOMContentLoaded.window="darkMode = document.documentElement.classList.contains('dark')"
     @theme-changed.window="darkMode = $event.detail.isDark"
     class="page-wrapper">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-inner">
            <div class="max-w-2xl">
                <h1 class="page-header-title">Katalog Buku</h1>
                <p class="page-header-desc mb-5">
                    Koleksi publikasi ilmiah dan karya akademik terpilih. Setiap volume dipilih berdasarkan bobot intelektual dan kontribusi pada perkembangan ilmu pengetahuan.
                </p>
                
                {{-- Search Bar --}}
                <form method="GET" action="{{ route('books.index') }}" class="relative">
                    <label for="search-books" class="sr-only">Cari buku</label>
                    <input id="search-books" type="text" name="q" value="{{ request('q') }}"
                           placeholder="Cari judul, penulis, atau ISBN..."
                           class="w-full border rounded-lg pl-10 pr-4 py-2.5 text-sm transition-all outline-none shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-gray-50 border-gray-200 text-gray-800 placeholder-gray-400 focus:bg-white dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:placeholder-gray-500 dark:focus:bg-gray-700">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    @if(request('category'))
                       <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                </form>
            </div>
        </div>
    </div>

    {{-- Main Layout --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
        <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
            
            {{-- Sidebar Filters --}}
            <aside class="w-full lg:w-52 xl:w-56 flex-shrink-0" x-data="{ 
                catOpen: window.innerWidth >= 1024
            }" @resize.window="catOpen = window.innerWidth >= 1024">
                <div class="lg:sticky lg:top-24">
                    {{-- Mobile: Collapsible filters --}}
                    <div class="flex gap-2 lg:hidden mb-3">
                        <button @click="catOpen = !catOpen" 
                                :aria-expanded="catOpen"
                                aria-controls="categories-section"
                                class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium shadow-sm transition-colors bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            <span>Filter Kategori</span>
                            <svg class="w-3.5 h-3.5 transition-transform" :class="catOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>

                    {{-- Categories Section --}}
                    <div id="categories-section" 
                         x-show="catOpen || window.innerWidth >= 1024"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="rounded-xl border p-4 mb-4 shadow-sm transition-all bg-white border-gray-100 dark:bg-gray-800 dark:border-gray-700"
                         role="region" 
                         aria-label="Filter Kategori">
                        <h3 class="text-[10px] font-bold tracking-widest uppercase mb-3 transition-colors text-gray-900 dark:text-gray-200">Kategori</h3>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('books.index', request()->except('category')) }}" 
                                   class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 {{ !request('category') ? 'bg-primary-50 text-primary-700 font-semibold dark:bg-primary-900/30 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <span>Semua</span>
                                    <span class="text-[9px] opacity-60">{{ $totalBooks ?? 0 }}</span>
                                </a>
                            </li>
                            @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('books.index', array_merge(request()->except('category'), ['category' => $cat->id])) }}" 
                                   class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 {{ request('category') == $cat->id ? 'bg-primary-50 text-primary-700 font-semibold dark:bg-primary-900/30 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <span class="break-words flex-1 text-left">{{ $cat->name }}</span>
                                    <span class="text-[9px] opacity-60 flex-shrink-0 ml-1">({{ $cat->books_count }})</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Curator's Note --}}
                    <div class="hidden md:block rounded-xl p-4 border shadow-sm bg-gradient-to-br from-amber-50 to-orange-50 border-amber-100 dark:from-amber-900/20 dark:to-orange-900/20 dark:border-amber-700">
                        <h4 class="text-xs font-bold mb-2 text-amber-900 dark:text-amber-300">💡 Pilihan Kurator</h4>
                        <p class="text-xs leading-relaxed text-amber-800 dark:text-amber-200">
                            Koleksi ilmiah berkualitas tinggi untuk pengembangan pengetahuan akademik.
                        </p>
                    </div>

                    @if(request('q') || request('category'))
                        <div class="mt-3 pt-3 lg:hidden border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('books.index') }}" class="text-sm font-medium transition-colors inline-flex items-center gap-1.5 text-gray-500 hover:text-primary-600 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Reset Filter
                            </a>
                        </div>
                    @endif
                </div>
            </aside>

            {{-- Book Grid --}}
            <div class="flex-1 min-w-0">
                @if($books->isEmpty())
                    <div class="text-center py-16 sm:py-20 flex flex-col items-center justify-center rounded-xl border bg-white border-gray-100 dark:bg-gray-800 dark:border-gray-700">
                        <svg class="w-14 h-14 mb-4 text-gray-200 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <p class="text-base font-medium mb-1 text-gray-500 dark:text-gray-300">Koleksi tidak ditemukan</p>
                        @if(request('q'))
                            <p class="text-sm text-gray-400">Tidak ada hasil untuk "{{ request('q') }}"</p>
                        @endif
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
                        @foreach($books as $book)
                            <x-book-card :book="$book" />
                        @endforeach
                    </div>

                    @if($books->hasPages())
                    <div class="mt-8 sm:mt-10">
                        <x-pagination :paginator="$books" />
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

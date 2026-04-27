@extends('layouts.public')

@section('title', 'Katalog Buku')
@section('meta_description', 'Katalog buku ilmiah ' . $siteSettings->get('site_name', ''))

@section('content')
<div x-data="{ darkMode: document.documentElement.classList.contains('dark') }" 
     @DOMContentLoaded.window="darkMode = document.documentElement.classList.contains('dark')"
     @theme-changed.window="darkMode = $event.detail.isDark"
     class="min-h-screen transition-colors duration-300 bg-[#FAFAF8] dark:bg-gray-900">
    
    {{-- Header --}}
    <div class="border-b transition-colors duration-300 bg-white border-gray-100 dark:bg-gray-800 dark:border-gray-700 pt-16 sm:pt-20 lg:pt-24">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 pb-5 sm:pb-8 md:pb-10 lg:pb-14">
            <div class="max-w-2xl">
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-4xl font-serif font-bold mb-2 sm:mb-3 md:mb-4 tracking-tight transition-colors duration-300 text-gray-900 dark:text-white leading-tight">Katalog Buku</h1>
                <p class="text-xs sm:text-sm md:text-base leading-relaxed mb-4 sm:mb-5 md:mb-6 transition-colors duration-300 text-gray-600 dark:text-gray-400">
                    Koleksi publikasi ilmiah dan karya akademik terpilih. Setiap volume dipilih berdasarkan bobot intelektual dan kontribusi pada perkembangan ilmu pengetahuan.
                </p>
                
                {{-- Search Bar --}}
                <form method="GET" action="{{ route('books.index') }}" class="relative">
                    <label for="search-books" class="sr-only">Cari buku</label>
                    <input id="search-books" type="text" name="q" value="{{ request('q') }}"
                           placeholder="Cari judul, penulis, atau ISBN..."
                           class="w-full border rounded-lg sm:rounded-full pl-10 sm:pl-12 pr-4 sm:pr-5 py-2.5 sm:py-3 text-xs sm:text-sm transition-all outline-none shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-gray-50 border-gray-200 text-gray-800 placeholder-gray-400 focus:bg-white dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:placeholder-gray-500 dark:focus:bg-gray-700">
                    <svg class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 w-4 sm:w-5 h-4 sm:h-5 text-gray-400 dark:text-gray-500 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    @if(request('category'))
                       <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                </form>
            </div>
    </div>

    {{-- Main Layout --}}
    <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-6 md:py-8 lg:py-10">
        <div class="flex flex-col lg:flex-row gap-4 sm:gap-6 lg:gap-8">
            
            {{-- Sidebar Filters --}}
            <aside class="w-full sm:w-64 md:w-56 lg:w-48 flex-shrink-0" x-data="{ 
                catOpen: window.innerWidth >= 1024, 
                filterOpen: false 
            }" @resize.window="catOpen = window.innerWidth >= 1024">
                <div class="lg:sticky lg:top-24">
                    {{-- Mobile: Collapsible filters --}}
                    <div class="flex gap-2 lg:hidden mb-3 sm:mb-4">
                        <button @click="catOpen = !catOpen" 
                                :aria-expanded="catOpen"
                                aria-controls="categories-section"
                                class="flex-1 flex items-center justify-center gap-2 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-medium shadow-sm transition-colors bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            <span>Kategori</span>
                        </button>
                    </div>

                    {{-- Categories Section --}}
                    <div id="categories-section" 
                         :class="catOpen ? 'block' : 'hidden'" 
                         class="lg:!block rounded-xl border p-3 sm:p-4 mb-3 sm:mb-4 shadow-sm transition-all bg-white border-gray-100 dark:bg-gray-800 dark:border-gray-700"
                         :style="catOpen || window.innerWidth >= 1024 ? '' : 'display: none'"
                         role="region" 
                         aria-label="Filter Kategori">
                        <h3 class="text-[9px] sm:text-[10px] font-bold tracking-widest uppercase mb-3 transition-colors duration-300 text-gray-900 dark:text-gray-200">Kategori</h3>
                        <ul class="space-y-1 sm:space-y-1.5">
                            <li>
                                <a href="{{ route('books.index', request()->except('category')) }}" 
                                   class="block px-3 py-2 sm:py-2.5 rounded-lg text-xs sm:text-xs font-medium transition-all duration-200"
                                   :class="!request('category') ? 'bg-primary-50 text-primary-700 font-semibold dark:bg-primary-900/30 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700'">
                                    <span class="flex items-center justify-between gap-2">
                                        <span class="flex-1">Semua</span>
                                        <span class="text-[8px] sm:text-[9px] opacity-70 flex-shrink-0">{{ $totalBooks ?? 0 }}</span>
                                </a>
                            </li>
                            @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('books.index', array_merge(request()->except('category'), ['category' => $cat->id])) }}" 
                                   class="block px-3 py-2.5 rounded-lg text-xs font-medium transition-all duration-200"
                                   :class="request('category') == {{ $cat->id }} ? 'bg-primary-50 text-primary-700 font-semibold dark:bg-primary-900/30 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700'">
                                    <span class="flex items-center justify-between gap-2">
                                        <span class="break-words flex-1 text-left">{{ $cat->name }}</span>
                                        <span class="text-[10px] opacity-70 flex-shrink-0 whitespace-nowrap">({{ $cat->books_count }})</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Curator's Note --}}
                    <div class="hidden md:block rounded-xl p-3 sm:p-4 border shadow-sm transition-colors duration-300 bg-gradient-to-br from-amber-50 to-orange-50 border-amber-100 dark:bg-amber-900/20 dark:border-amber-700"
                         :class="darkMode ? 'bg-amber-900/20 border-amber-700 from-amber-900/30 to-orange-900/30' : 'bg-gradient-to-br from-amber-50 to-orange-50 border-amber-100'">
                        <h4 class="text-xs sm:text-sm font-bold mb-2 sm:mb-2 transition-colors duration-300 text-amber-900 dark:text-amber-300"
                            :class="darkMode ? 'text-amber-300' : 'text-amber-900'">💡 Pilihan Kurator</h4>
                        <p class="text-xs sm:text-sm leading-relaxed transition-colors duration-300 text-amber-800 dark:text-amber-200"
                           :class="darkMode ? 'text-amber-200' : 'text-amber-800'">
                            Koleksi ilmiah berkualitas tinggi untuk pengembangan pengetahuan akademik.
                        </p>
                    </div>

                    @if(request('q') || request('category'))
                        <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 lg:hidden transition-colors duration-300 border-t border-gray-100 dark:border-gray-700"
                             :class="darkMode ? 'border-t border-gray-700' : 'border-t border-gray-100'">
                            <a href="{{ route('books.index') }}" class="text-xs sm:text-sm font-medium transition-colors inline-flex items-center gap-1.5 hover:text-primary-600 text-gray-500 dark:text-gray-400"
                               :class="darkMode ? 'text-gray-400' : 'text-gray-500'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>Reset Filter</span>
                            </a>
                        </div>
                    @endif
                </div>
            </aside>

            {{-- Book Grid --}}
            <div class="flex-1 min-w-0">
                @if($books->isEmpty())
                    <div class="text-center py-12 sm:py-20 flex flex-col items-center justify-center rounded-xl border transition-colors duration-300 bg-white border-gray-100 dark:bg-gray-800 dark:border-gray-700"
                         :class="darkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100'">
                        <svg class="w-12 h-12 sm:w-16 sm:h-16 mb-3 sm:mb-4 transition-colors duration-300 text-gray-200 dark:text-gray-600"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             :class="darkMode ? 'text-gray-600' : 'text-gray-200'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <p class="text-base sm:text-lg font-medium mb-1 transition-colors duration-300 text-gray-500 dark:text-gray-300"
                           :class="darkMode ? 'text-gray-300' : 'text-gray-500'">Koleksi tidak ditemukan</p>
                        @if(request('q'))
                            <p class="text-xs sm:text-sm transition-colors duration-300 text-gray-400 dark:text-gray-400"
                               :class="darkMode ? 'text-gray-400' : 'text-gray-400'">Tidak ada hasil untuk "{{ request('q') }}"</p>
                        @endif
                    </div>
                @else
                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 md:gap-5 lg:gap-6">
                        @foreach($books as $book)
                            <x-book-card :book="$book" />
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($books->hasPages())
                    <div class="mt-8 sm:mt-12">
                        <x-pagination :paginator="$books" />
                    </div>
                    @endif
                @endif
            </div>
    </div>
@endsection

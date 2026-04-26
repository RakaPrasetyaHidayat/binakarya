@extends('layouts.public')

@section('title', 'Blog')

@section('content')
    {{-- Page wrapper — navbar clearance shares same bg as content --}}
    <div class="min-h-screen bg-white dark:bg-slate-900 transition-colors duration-300">

        {{-- Navbar clearance gap --}}
        <div class="pt-16 sm:pt-20 lg:pt-24"></div>

        <section class="border-b transition-colors duration-300 bg-white border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6 sm:pb-8 md:pb-10">
                <span class="text-xs uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400 font-semibold mb-2 block transition-colors">Blog & Artikel</span>
                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-sans font-bold text-gray-900 dark:text-white mb-3 sm:mb-4 transition-colors">Artikel & Wawasan</h1>
                <p class="text-xs sm:text-base text-gray-600 dark:text-gray-300 max-w-2xl transition-colors">Dapatkan informasi terbaru, tips, dan wawasan seputar program pemberdayaan dan kependidikan dari Bina Karya Cendekia.</p>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <form method="GET" action="{{ route('blog.index') }}"
                class="flex flex-col gap-2 sm:gap-3 sm:flex-row mb-10 sm:mb-12 bg-white dark:bg-slate-800 p-3 sm:p-5 rounded-xl shadow-sm dark:shadow-lg border border-gray-100 dark:border-gray-700 transition-colors duration-300">
                <div class="flex-1 relative">
                    <ion-icon name="search-outline"
                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-sm"></ion-icon>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari artikel..."
                        class="w-full pl-9 pr-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-xs sm:text-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 transition">
                </div>
                <div class="sm:w-48">
                    <select name="category"
                        class="w-full py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-xs sm:text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 transition">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="bg-primary-600 text-white px-4 sm:px-6 py-2 rounded-lg text-xs sm:text-sm font-bold hover:bg-primary-700 transition shadow-md shadow-primary-100 dark:shadow-primary-900/30">
                    Filter
                </button>
                @if(request('q') || request('category'))
                    <a href="{{ route('blog.index') }}"
                        class="flex items-center justify-center px-3 py-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 text-xs sm:text-sm font-medium transition">
                        Reset
                    </a>
                @endif
            </form>

            @if($posts->isEmpty())
                <div class="text-center py-16 bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 transition-colors duration-300">
                    <ion-icon name="document-text-outline" class="text-5xl text-gray-200 dark:text-gray-700 mb-3"></ion-icon>
                    <p class="text-xs sm:text-sm text-gray-400 dark:text-gray-500">Maaf, artikel yang Anda cari tidak ditemukan.</p>
                </div>
            @else
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-5 lg:gap-6">
                    @foreach($posts as $post)
                        <x-blog-card :post="$post" />
                    @endforeach
                </div>
                <x-pagination :paginator="$posts" />
            @endif
        </div>

    </div>{{-- end page wrapper --}}
@endsection

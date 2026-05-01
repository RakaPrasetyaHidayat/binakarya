@extends('layouts.public')

@section('title', 'Blog')

@section('content')
<div class="page-wrapper">

    {{-- Page Header --}}
    <div class="page-header">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs><pattern id="grid-blog" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/></pattern></defs>
                <rect width="100" height="100" fill="url(#grid-blog)"/>
            </svg>
        </div>
        <div class="page-header-inner">
            <span class="page-header-tagline">Blog & Artikel</span>
            <h1 class="page-header-title">Artikel & Wawasan</h1>
            <p class="page-header-desc max-w-2xl">Dapatkan informasi terbaru, tips, dan wawasan seputar program pemberdayaan dan kependidikan dari Bina Karya Cendekia.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 lg:py-12">

        {{-- Filter Bar --}}
        <form method="GET" action="{{ route('blog.index') }}"
            class="flex flex-col sm:flex-row gap-2 sm:gap-3 mb-8 sm:mb-10 bg-white dark:bg-slate-800 p-3 sm:p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-300">
            <div class="flex-1 relative">
                <ion-icon name="search-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-sm pointer-events-none"></ion-icon>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari artikel..."
                    class="w-full pl-9 pr-3 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition">
            </div>
            <div class="w-full sm:w-44">
                <select name="category"
                    class="w-full py-2.5 px-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                class="bg-primary-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-primary-700 transition shadow-sm whitespace-nowrap">
                Filter
            </button>
            @if(request('q') || request('category'))
                <a href="{{ route('blog.index') }}"
                    class="flex items-center justify-center px-3 py-2.5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 text-sm font-medium transition whitespace-nowrap">
                    Reset
                </a>
            @endif
        </form>

        @if($posts->isEmpty())
            <div class="text-center py-16 sm:py-20 bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <ion-icon name="document-text-outline" class="text-5xl text-gray-200 dark:text-gray-700 mb-3"></ion-icon>
                <p class="text-sm text-gray-400 dark:text-gray-500">Maaf, artikel yang Anda cari tidak ditemukan.</p>
            </div>
        @else
            <div class="card-grid-3">
                @foreach($posts as $post)
                    <x-blog-card :post="$post" />
                @endforeach
            </div>
            <x-pagination :paginator="$posts" />
        @endif

    </div>
</div>
@endsection

@extends('layouts.public')

@section('title')
{{ $page->title }}
@endsection

@section('meta_description')
{{ $page->meta_description }}
@endsection

@section('content')
{{-- Page wrapper with consistent bg --}}
<div class="min-h-screen bg-gray-50 dark:bg-slate-900 transition-colors duration-300">

    <div class="pt-20 sm:pt-24 lg:pt-32 pb-10 sm:pb-16 lg:pb-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumbs --}}
            <nav class="flex mb-8 text-xs sm:text-sm text-gray-400 dark:text-gray-500 uppercase tracking-widest font-bold items-center gap-2">
                <a href="{{ route('homepage') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Beranda</a>
                <ion-icon name="chevron-forward-outline" class="text-[10px]"></ion-icon>
                <span class="text-gray-600 dark:text-gray-300">{{ $page->title }}</span>
            </nav>

            <article class="bg-white dark:bg-slate-800 shadow-xl shadow-gray-200/50 dark:shadow-black/20 rounded-3xl overflow-hidden border border-gray-100 dark:border-slate-700 transition-all duration-300">
                <div class="px-6 py-10 sm:p-12 lg:p-16">
                    <header class="mb-10 text-center">
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-poppins font-bold text-gray-900 dark:text-white leading-tight mb-4 transition-colors">
                            {{ $page->title }}
                        </h1>
                        <div class="w-20 h-1.5 bg-primary-600 dark:bg-primary-500 mx-auto rounded-full"></div>
                    </header>

                    <div class="prose prose-lg dark:prose-invert max-w-none prose-headings:font-poppins prose-headings:font-bold prose-headings:text-gray-900 dark:prose-headings:text-white prose-p:text-gray-600 dark:prose-p:text-gray-300 prose-p:leading-relaxed prose-a:text-primary-600 dark:prose-a:text-primary-400 prose-a:no-underline hover:prose-a:underline transition-colors">
                        {!! $page->content !!}
                    </div>
                </div>

                <div class="bg-gray-50/50 dark:bg-slate-900/30 px-6 py-6 sm:px-12 border-t border-gray-100 dark:border-slate-700/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-xs text-gray-400 dark:text-gray-500 italic tracking-wide">Terakhir diperbarui pada {{ $page->updated_at->format('d F Y') }}</p>
                    @if($siteSettings->get('wa_number'))
                    <a href="https://wa.me/{{ $siteSettings->get('wa_number') }}" target="_blank" class="text-sm font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700 transition-colors flex items-center gap-2">
                        Tanyakan sesuatu <ion-icon name="logo-whatsapp" class="text-lg"></ion-icon>
                    </a>
                    @endif
                </div>
            </article>

        </div>
    </div>

</div>
@endsection

@extends('layouts.public')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-16">
    <div class="text-center max-w-md mx-auto">
        <div class="relative mb-8">
            <div class="w-28 h-28 sm:w-32 sm:h-32 mx-auto rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center">
                <svg class="w-14 h-14 sm:w-16 sm:h-16 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="absolute -top-2 -right-2 w-8 h-8 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center">
                <span class="text-primary-500 text-lg font-bold">?</span>
            </div>
        </div>
        
        <h1 class="text-7xl sm:text-8xl font-bold text-gray-900 dark:text-white mb-2 tracking-tighter">404</h1>
        <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Halaman Tidak Ditemukan</h2>
        <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 mb-8 leading-relaxed max-w-sm mx-auto">
            Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman telah dipindahkan atau URL yang Anda masukkan salah.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('homepage') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl transition shadow-lg shadow-primary-500/20">
                <ion-icon name="home-outline"></ion-icon>
                Kembali ke Beranda
            </a>
            <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <ion-icon name="mail-outline"></ion-icon>
                Hubungi Kami
            </a>
        </div>
        
        <div class="mt-10 pt-8 border-t border-gray-100 dark:border-gray-800">
            <p class="text-xs text-gray-400 dark:text-gray-500">
                Error 404 &middot; Not Found &middot; {{ now()->format('d M Y H:i') }}
            </p>
        </div>
    </div>
</div>
@endsection


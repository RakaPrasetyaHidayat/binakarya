@extends('layouts.admin')

@section('title', 'Kustomisasi Halaman Website')

@section('content')
<div class="max-w-6xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Kustomisasi Halaman Website</h1>
        <p class="text-gray-600 dark:text-gray-400">Kelola konten, layout, dan styling semua halaman website dari satu dashboard</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($pages as $pageKey => $pageLabel)
            <a href="{{ route('admin.page-customizer.show', $pageKey) }}" 
               class="group bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 p-8 hover:shadow-lg dark:hover:shadow-lg dark:hover:shadow-primary-500/10 transition-all overflow-hidden relative">
                
                <div class="absolute inset-0 bg-gradient-to-br from-primary-500/0 to-primary-500/0 group-hover:from-primary-500/5 group-hover:to-primary-500/10 transition-all"></div>
                
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-gradient-to-br from-primary-100 to-blue-100 dark:from-primary-900/30 dark:to-blue-900/30 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        @switch($pageKey)
                            @case('homepage')
                                <svg class="w-7 h-7 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 16l4-4m0 0l4 4m-4-4V5m0 16H9"></path></svg>
                                @break
                            @case('about')
                                <svg class="w-7 h-7 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @break
                            @case('contact')
                                <svg class="w-7 h-7 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                @break
                            @case('services')
                                <svg class="w-7 h-7 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.728 0-7.196-.54-10.393-1.558m21.374-5.004l-.155.648m0 0A23.882 23.882 0 0015.13 5m0 0a23.964 23.964 0 012.013-3.188m-16.46 0a23.964 23.964 0 012.013 3.188m0 0A23.882 23.882 0 0012 12.75m0 0A23.882 23.882 0 0112 15m0 0A23.882 23.882 0 015.13 5"></path></svg>
                                @break
                            @default
                                <svg class="w-7 h-7 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                        @endswitch
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition">{{ $pageLabel }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Kelola konten, section, layout, dan styling</p>
                    
                    <div class="flex items-center text-primary-600 dark:text-primary-400 text-sm font-medium">
                        Buka Customizer
                        <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Info Box --}}
    <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl border border-blue-200 dark:border-blue-800/50 p-6">
        <div class="flex gap-4">
            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-blue-900 dark:text-blue-300 mb-1">Tips Menggunakan Page Customizer</h4>
                <ul class="text-sm text-blue-800 dark:text-blue-400 space-y-1">
                    <li>✓ Setiap halaman terbagi ke dalam section yang dapat dikustomisasi secara independen</li>
                    <li>✓ Upload gambar berkualitas tinggi untuk hasil terbaik (JPG, PNG, atau WebP)</li>
                    <li>✓ Semua perubahan tersimpan otomatis dan dapat langsung dilihat di website</li>
                    <li>✓ Gunakan text yang ringkas dan menarik untuk best user experience</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

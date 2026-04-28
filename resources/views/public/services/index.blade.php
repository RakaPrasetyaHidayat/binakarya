@extends('layouts.public')

@section('title', $siteSettings->get('services_header_title', 'Layanan'))

@section('content')
@php
    $gridColumns = $siteSettings->get('services_layout_grid_columns', 3);
    $gridClass = $gridColumns == 1 ? 'card-grid-2' : ($gridColumns == 2 ? 'card-grid-2' : 'card-grid-3');
    $showExcerpt = $siteSettings->get('services_layout_show_excerpt');
@endphp

<div class="page-wrapper">

    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-inner">
            <span class="page-header-tagline">{{ $siteSettings->get('services_header_tagline', 'Layanan Kami') }}</span>
            <h1 class="page-header-title">{{ $siteSettings->get('services_header_title', 'Solusi dan Layanan') }}</h1>
            <p class="page-header-desc max-w-2xl">{{ $siteSettings->get('services_header_description', 'Kami menyediakan layanan lengkap untuk mendukung pengembangan ilmu pengetahuan dan publikasi berkualitas') }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 lg:py-12">

    @if($services->isEmpty())
        <div class="text-center py-16 sm:py-20 text-gray-400 dark:text-gray-500 text-sm">Belum ada layanan tersedia.</div>
    @else
        <div class="{{ $gridClass }}">
            @foreach($services as $service)
            <a href="{{ $service->external_url ?: route('services.show', $service->slug) }}" 
               @if($service->external_url) target="_blank" rel="noopener noreferrer" @endif
               class="group bg-white dark:bg-slate-800 rounded-xl p-5 sm:p-6 lg:p-7 shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 transition-all hover:-translate-y-1">
                
                <div class="flex items-center gap-3 mb-3">
                    @if($service->icon)
                        <div class="flex-shrink-0">
                            @if(str_contains($service->icon, '-outline') || str_contains($service->icon, 'logo-'))
                                <ion-icon name="{{ $service->icon }}" class="text-2xl text-primary-600 dark:text-primary-400"></ion-icon>
                            @else
                                <span class="text-2xl">{{ $service->icon }}</span>
                            @endif
                        </div>
                    @endif
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition">{{ $service->title }}</h2>
                    @if($service->external_url)
                        <ion-icon name="open-outline" class="text-xs opacity-50 ml-auto"></ion-icon>
                    @endif
                </div>

                @if($showExcerpt)
                    <div class="text-gray-600 dark:text-gray-400 text-sm mb-4 leading-relaxed transition-colors line-clamp-3">{!! $service->excerpt !!}</div>
                @endif
                <span class="text-primary-600 dark:text-primary-400 text-sm font-medium flex items-center gap-1">
                    {{ $service->external_url ? 'Kunjungi Layanan' : 'Selengkapnya' }} 
                    <ion-icon name="arrow-forward-outline" class="text-xs"></ion-icon>
                </span>
            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <x-pagination :paginator="$services" />
    @endif

    @if($siteSettings->get('services_cta_title') || $siteSettings->get('services_cta_description') || $siteSettings->get('services_cta_button_text'))
    <section class="mt-12 sm:mt-16 rounded-2xl p-6 sm:p-8 text-gray-900 dark:text-white">
        <div class="text-center max-w-3xl mx-auto">
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-3 sm:mb-4">{{ $siteSettings->get('services_cta_title', 'Masih Bingung?') }}</h2>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 mb-6 sm:mb-8 leading-relaxed">{{ $siteSettings->get('services_cta_description', 'Hubungi tim kami untuk diskusi lebih lanjut dan dapatkan solusi yang tepat untuk kebutuhan organisasi Anda.') }}</p>
            @if($siteSettings->get('services_cta_button_text'))
                <a href="{{ $siteSettings->get('cta_button_link', route('contact')) }}"
                   class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 sm:px-8 py-2.5 sm:py-3 shadow-lg transition text-sm sm:text-base">
                    {{ $siteSettings->get('services_cta_button_text') }}
                </a>
            @endif
        </div>
    </section>
    @endif

    </div>

</div>
@endsection

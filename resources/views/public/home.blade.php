@extends('layouts.public')

@section('title', $siteSettings->get('site_name', 'Beranda'))

@section('content')

    {{-- Hero Section - Vision Center Style --}}
    @php
        $heroImage = $siteSettings->get('hero_image');
        $heroImageUrl = $heroImage ? asset('storage/' . $heroImage) : null;
        $heroTagline = trim($siteSettings->get('hero_tagline', '')) ?: 'Dedikasi Ilmiah, Publikasi Berintegritas, Literasi Tanpa Batas';
        $heroTitle = trim($siteSettings->get('hero_title', '')) ?: 'Bina Karya Cendekia';
        if (strcasecmp($heroTagline, $heroTitle) === 0) {
            $heroTagline = 'Membangun Publikasi Ilmiah dengan Kesan Profesional';
        }
    @endphp
    <section class="relative overflow-hidden transition-colors duration-300"
        :class="darkMode ? 'bg-slate-900 text-white' : 'bg-white text-slate-900'">

        <div class="w-full max-w-6xl mx-auto px-4 sm:px-8 lg:px-12">
            {{-- Mobile: kolom (teks atas, gambar bawah) | Desktop: baris (teks kiri, gambar kanan) --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:min-h-[85vh] sm:gap-12 lg:gap-20 py-12 sm:py-0">

                {{-- Left: Text — full width mobile, flex-1 desktop --}}
                <div class="flex-1 min-w-0 order-1 sm:py-20">

                    {{-- Tagline --}}
                    <p class="text-[10px] sm:text-xs font-bold text-primary-600 dark:text-primary-400 uppercase tracking-widest mb-3 leading-relaxed">
                        {{ $heroTagline }}
                    </p>

                    {{-- Title --}}
                    <h1 class="text-3xl sm:text-4xl lg:text-6xl xl:text-7xl font-extrabold leading-tight mb-4 transition-colors"
                        :class="darkMode ? 'text-white' : 'text-slate-900'">
                        {!! e($heroTitle) !!}
                    </h1>

                    {{-- Description --}}
                    <p class="text-sm sm:text-base lg:text-lg leading-relaxed font-medium opacity-70 mb-6 transition-colors max-w-lg"
                        :class="darkMode ? 'text-gray-300' : 'text-slate-600'">
                        {{ trim($siteSettings->get('hero_description')) ?: 'Lembaga pelatihan dan penerbitan yang berdedikasi untuk mencetak generasi unggul melalui publikasi ilmiah berintegritas dan pengembangan softskills.' }}
                    </p>

                    {{-- CTA --}}
                    <div class="flex flex-row flex-wrap items-center gap-3">
                        <a href="{{ route('about') }}"
                            class="inline-flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg transition shadow-md active:scale-95 text-sm">
                            <span>Tentang Kami</span>
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="https://wa.me/62895611314372?text=Halo,%20saya%20ingin%20konsultasi%20gratis" target="_blank" rel="noopener"
                            class="inline-flex items-center justify-center gap-1.5 bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-2.5 rounded-lg transition shadow-md active:scale-95 text-sm">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            <span>WhatsApp</span>
                        </a>
                    </div>
                </div>

                {{-- Right: Image --}}
                <div class="order-2 w-full sm:w-auto sm:flex-shrink-0">

                    {{-- Mobile only: gambar di bawah teks, proporsional --}}
                    <div class="block sm:hidden w-full overflow-hidden rounded-2xl mt-6 mb-4">
                        @if($heroImageUrl)
                            <img src="{{ $heroImageUrl }}" alt="Hero Image"
                                 class="w-full max-h-72 object-cover object-top rounded-2xl">
                        @else
                            <div class="w-full h-48 flex items-center justify-center rounded-2xl"
                                :class="darkMode ? 'bg-slate-800' : 'bg-gray-100'">
                                <svg class="w-10 h-10 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                    </div>

                    {{-- Tablet & Desktop --}}
                    <div class="hidden sm:block sm:w-[280px] md:w-[300px] lg:w-[360px] xl:w-[400px]">
                        <div class="aspect-[4/5] w-full overflow-hidden rounded-2xl shadow-2xl"
                             style="max-height: 70vh;"
                             :class="darkMode ? 'bg-slate-800 border border-slate-700' : 'bg-gray-100 border border-gray-200'">
                            @if($heroImageUrl)
                                <img src="{{ $heroImageUrl }}" alt="Hero Image" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-center p-4"
                                    :class="darkMode ? 'text-slate-500' : 'text-slate-400'">
                                    <svg class="w-10 h-10 mx-auto mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-xs opacity-30">Hero Image</span>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    @php $sectionCount = 0; @endphp

    {{-- Section 2: About Section --}}
    @php
        $aboutImages = collect([
            $siteSettings->get('about_img_1'),
            $siteSettings->get('about_img_2'),
            $siteSettings->get('about_img_3'),
            $siteSettings->get('about_img_4'),
        ])->filter();
        $bgClass = ($sectionCount++ % 2 == 0) ? 'bg-gray-100 dark:bg-slate-800' : 'bg-white dark:bg-slate-800';
    @endphp
    <section class="py-10 sm:py-14 lg:py-18 {{ $bgClass }} transition-colors duration-300">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Centered Header --}}
            <div class="text-center mb-10 sm:mb-12">
                <span class="inline-block text-xs uppercase tracking-[0.3em] text-primary-600 dark:text-primary-400 font-bold mb-3 transition-colors px-3 py-1.5 bg-primary-50 dark:bg-primary-900/30 rounded-full text-[10px]">Tentang Kami</span>
                <h2 class="text-lg sm:text-2xl lg:text-2xl font-sans font-bold text-gray-900 dark:text-white mb-4 transition-colors">
                    Bina Karya <span class="text-primary-600">Cendekia</span>
                </h2>
                <div class="w-16 h-0.5 bg-primary-500 mx-auto mb-4 rounded-full"></div>
                <p class="text-xs sm:text-base text-gray-600 dark:text-gray-300 leading-relaxed transition-colors max-w-3xl mx-auto">
                    {{ strip_tags($siteSettings->get('about_profile', 'Bina Karya Cendekia adalah lembaga penerbitan dan pengembangan ilmu pengetahuan yang berdedikasi untuk memajukan literasi dan penelitian di Indonesia.')) }}
                </p>
            </div>

            {{-- Vision & Mission Cards - Style Vision Center --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-4xl mx-auto mb-10 items-stretch">
                {{-- Visi - full width mobile, 1/3 desktop --}}
                <div class="sm:col-span-1 bg-primary-600 dark:bg-primary-700 rounded-2xl p-8 shadow-lg flex flex-col gap-5">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-yellow-300 font-bold text-lg sm:text-xl mb-3">Visi Kami</h3>
                        <p class="text-white/90 text-base sm:text-lg leading-relaxed italic">
                            "Menjadi pusat publikasi ilmiah berkualitas yang mendorong kemajuan ilmu pengetahuan dan literasi di Indonesia."
                        </p>
                    </div>
                </div>

                {{-- Misi - full width mobile, 2/3 desktop --}}
                <div class="sm:col-span-2 bg-white dark:bg-slate-700 rounded-2xl p-8 shadow-lg flex flex-col gap-5 border border-gray-100 dark:border-slate-600">
                    <div>
                        <h3 class="text-primary-600 dark:text-primary-400 font-bold text-lg sm:text-xl mb-5">Misi Strategis</h3>
                        <ol class="space-y-4">
                            <li class="flex items-start gap-4">
                                <span class="flex-shrink-0 w-7 h-7 rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-600 dark:text-primary-400 text-sm font-bold flex items-center justify-center">1</span>
                                <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300 leading-relaxed">Menerbitkan karya ilmiah berkualitas dengan standar akademik dan proses peer-review yang ketat.</p>
                            </li>
                            <li class="flex items-start gap-4">
                                <span class="flex-shrink-0 w-7 h-7 rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-600 dark:text-primary-400 text-sm font-bold flex items-center justify-center">2</span>
                                <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300 leading-relaxed">Mendukung penulis dan peneliti dengan layanan penerbitan terintegrasi dari naskah hingga distribusi.</p>
                            </li>
                            <li class="flex items-start gap-4">
                                <span class="flex-shrink-0 w-7 h-7 rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-600 dark:text-primary-400 text-sm font-bold flex items-center justify-center">3</span>
                                <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300 leading-relaxed">Membangun ekosistem literasi ilmiah yang inklusif dan mudah diakses oleh seluruh lapisan masyarakat.</p>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>

            {{-- About Images Gallery --}}
            @if($aboutImages->count())
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 max-w-3xl mx-auto">
                @foreach($aboutImages as $image)
                    <div class="rounded-lg overflow-hidden shadow-sm aspect-square group">
                        <img src="{{ asset('storage/' . $image) }}" alt="Tentang Kami" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </div>
                @endforeach
            </div>
            @endif

        </div>
    </section>

    {{-- Section 3: Layanan - bg-white / dark:bg-slate-900 --}}
    @php
        $serviceImages = collect([
            $siteSettings->get('service_img_1'),
            $siteSettings->get('service_img_2'),
            $siteSettings->get('service_img_3'),
            $siteSettings->get('service_img_4'),
        ]);
    @endphp
    @if($services->count())
        @php $bgClass = ($sectionCount++ % 2 == 0) ? 'bg-white dark:bg-slate-900' : 'bg-gray-100 dark:bg-slate-800'; @endphp
        <section class="py-10 sm:py-14 lg:py-18 {{ $bgClass }} transition-colors duration-300">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-8 sm:mb-10">
                    <span class="text-xs uppercase tracking-[0.2em] text-gray-900 dark:text-gray-100 font-semibold mb-2 block transition-colors text-[10px]">Layanan Kami</span>
                    <h2 class="text-lg sm:text-2xl lg:text-2xl font-sans font-bold text-gray-900 dark:text-white mb-2 transition-colors">
                        Layanan Unggulan</h2>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 max-w-xl mx-auto transition-colors">Layanan berkualitas profesional dan modern.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5 lg:gap-6 max-w-6xl mx-auto" id="services-grid">
                    {{-- Card 1: Pelatihan Akademik --}}
                    <a href="https://visioncenter.id" target="_blank" rel="noopener noreferrer"
                        class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all group flex flex-col">
                        <div class="h-1 sm:h-1.5 bg-gradient-to-r from-primary-500 to-primary-600 flex-shrink-0"></div>
                        <div class="p-4 sm:p-6 lg:p-7 flex flex-col flex-1 items-center text-center">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center mb-3 sm:mb-4 group-hover:scale-105 transition-transform text-xl sm:text-2xl flex-shrink-0">
                                📚
                            </div>
                            <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-900 dark:text-white mb-2 sm:mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition leading-tight">
                                Pelatihan Akademik
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-3 sm:mb-4 line-clamp-3 flex-1">
                                Program pelatihan akademik untuk pengembangan kompetensi.</p>
                            <span class="text-primary-600 dark:text-primary-400 text-xs sm:text-sm font-medium inline-flex items-center gap-1 mt-auto">
                                Pelajari
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </a>

                    {{-- Card 2: Publikasi Buku --}}
                    <a href="{{ route('books.index') }}"
                        class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all group flex flex-col">
                        <div class="h-1 sm:h-1.5 bg-gradient-to-r from-primary-500 to-primary-600 flex-shrink-0"></div>
                        <div class="p-4 sm:p-6 lg:p-7 flex flex-col flex-1 items-center text-center">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center mb-3 sm:mb-4 group-hover:scale-105 transition-transform text-xl sm:text-2xl flex-shrink-0">
                                📖
                            </div>
                            <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-900 dark:text-white mb-2 sm:mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition leading-tight">
                                Publikasi Buku
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-3 sm:mb-4 line-clamp-3 flex-1">
                                Penerbitan karya ilmiah berkualitas dengan standar akademik tinggi.</p>
                            <span class="text-primary-600 dark:text-primary-400 text-xs sm:text-sm font-medium inline-flex items-center gap-1 mt-auto">
                                Lihat Katalog
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </a>

                    {{-- Card 3: Publikasi Jurnal --}}
                    <a href="https://journal.binakaryacendekia.id" target="_blank" rel="noopener noreferrer"
                        class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all group flex flex-col">
                        <div class="h-1 sm:h-1.5 bg-gradient-to-r from-primary-500 to-primary-600 flex-shrink-0"></div>
                        <div class="p-4 sm:p-6 lg:p-7 flex flex-col flex-1 items-center text-center">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center mb-3 sm:mb-4 group-hover:scale-105 transition-transform text-xl sm:text-2xl flex-shrink-0">
                                📝
                            </div>
                            <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-900 dark:text-white mb-2 sm:mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition leading-tight">
                                Publikasi Jurnal
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-3 sm:mb-4 line-clamp-3 flex-1">
                                Jurnal ilmiah peer-reviewed dengan standar internasional.
                            </p>
                            <span class="text-primary-600 dark:text-primary-400 text-xs sm:text-sm font-medium inline-flex items-center gap-1 mt-auto">
                                Kunjungi
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </a>
                </div>

                <div class="text-center mt-6 sm:mt-8">
                    <a href="{{ route('services.index') }}"
                        class="border-2 border-primary-600 text-primary-600 dark:text-primary-400 px-5 sm:px-6 py-2 sm:py-2.5 rounded-lg hover:bg-primary-600 hover:text-white transition font-semibold text-xs sm:text-sm"
                        style="display:inline-block;text-align:center;">
                        Lihat Semua Layanan &rarr;
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- Section 4: Buku Terbaru --}}
    @if($latestBooks->count())
        @php $bgClass = ($sectionCount++ % 2 == 0) ? 'bg-white dark:bg-slate-900' : 'bg-gray-100 dark:bg-slate-800'; @endphp
        <section class="py-10 sm:py-12 lg:py-16 {{ $bgClass }} transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-8 sm:mb-10">
                    <span class="text-xs uppercase tracking-widest text-primary-600 font-semibold text-[10px]">Publikasi</span>
                    <h2 class="text-lg sm:text-2xl lg:text-2xl font-sans font-bold text-gray-900 dark:text-white mt-1 mb-2 transition-colors">Koleksi Buku Terbaru</h2>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 max-w-xl mx-auto transition-colors">Publikasi ilmiah terbaru dari tim kami</p>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-5 lg:gap-6">
                    @foreach($latestBooks as $book)
                        <x-book-card :book="$book" />
                    @endforeach
                </div>

                <div class="text-center mt-6 sm:mt-8">
                    <a href="{{ route('books.index') }}"
                        class="bg-primary-600 text-white px-5 sm:px-6 py-2 sm:py-2.5 rounded-lg hover:bg-primary-700 transition shadow-md font-medium text-xs sm:text-sm"
                        style="display:inline-block;text-align:center;">
                        Lihat Semua Katalog &rarr;
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- Section 5: Featured Quote --}}
    @php $bgClass = ($sectionCount++ % 2 == 0) ? 'bg-white dark:bg-slate-900' : 'bg-gray-100 dark:bg-slate-800'; @endphp
    <section class="py-8 sm:py-10 lg:py-14 {{ $bgClass }} transition-colors duration-300">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="w-6 h-6 sm:w-8 sm:h-8 mx-auto mb-3 sm:mb-4 flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-primary-300 dark:text-primary-600" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                    </svg>
                </div>
                <blockquote class="text-base sm:text-lg lg:text-xl font-sans text-gray-800 dark:text-gray-100 italic leading-relaxed mb-3 sm:mb-4 transition-colors px-2">
                    "{{ trim($siteSettings->get('quote_text')) ?: 'Kurasi adalah bentuk kreasi baru. Kami percaya bahwa setiap karya ilmiah berhak mendapatkan perhatian yang layak.' }}"
                </blockquote>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 uppercase tracking-widest font-medium transition-colors">
                    — {{ trim($siteSettings->get('quote_author')) ?: 'Bina Karya Cendekia Foundation' }}</p>
                <div class="w-6 h-px bg-primary-300 dark:bg-primary-600 mx-auto mt-3 sm:mt-4 transition-colors"></div>
            </div>
        </div>
    </section>

    {{-- Section 6: Testimonials --}}
    @if($testimonials->count())
        @php $bgClass = ($sectionCount++ % 2 == 0) ? 'bg-white dark:bg-slate-900' : 'bg-gray-100 dark:bg-slate-800'; @endphp
        <section class="py-8 sm:py-12 lg:py-16 {{ $bgClass }} transition-colors duration-300">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-6 sm:mb-10">
                    <span class="text-xs uppercase tracking-[0.2em] text-primary-600 font-semibold mb-2 block transition-colors text-[10px]">Testimoni</span>
                    <h2 class="text-lg sm:text-2xl lg:text-2xl font-sans font-bold text-gray-900 dark:text-white mb-2 transition-colors">
                        Kepercayaan dari Pengguna Kami</h2>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 max-w-xl mx-auto transition-colors">Ulasan pengguna yang puas dengan layanan kami.</p>
                </div>

                <x-testimonials-carousel :testimonials="$testimonials" />
            </div>
        </section>
    @endif

    {{-- Section 7: Blog Terbaru --}}
    @if($latestPosts->count())
        @php $bgClass = ($sectionCount++ % 2 == 0) ? 'bg-white dark:bg-slate-900' : 'bg-gray-100 dark:bg-slate-800'; @endphp
        <section class="py-12 sm:py-16 lg:py-24 {{ $bgClass }} transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10 sm:mb-12">
                    <span class="text-xs uppercase tracking-widest text-primary-600 font-semibold">Artikel</span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-sans font-bold text-gray-900 dark:text-white mt-1 mb-3 sm:mb-4 transition-colors">
                        Artikel Terbaru</h2>
                    <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 max-w-xl mx-auto transition-colors">Insight dan informasi terkini dari tim kami</p>
                </div>
                
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-5 lg:gap-6">
                    @foreach($latestPosts as $post)
                        <x-blog-card :post="$post" />
                    @endforeach
                </div>

                <div class="text-center mt-10 sm:mt-12">
                    <a href="{{ route('blog.index') }}"
                        class="border-2 border-primary-600 text-primary-600 dark:text-primary-400 px-6 sm:px-8 py-2 sm:py-2.5 rounded-lg hover:bg-primary-600 hover:text-white transition font-medium text-sm sm:text-base"
                        style="display:inline-block;text-align:center;">
                        Lihat Semua Artikel &rarr;
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- Section 8: Subscribe Newsletter --}}
    @php $bgClass = ($sectionCount++ % 2 == 0) ? 'bg-white dark:bg-slate-900' : 'bg-gray-100 dark:bg-slate-800'; @endphp
    <section class="py-12 sm:py-16 lg:py-20 {{ $bgClass }} transition-colors duration-300">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

            <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-3 transition-colors">
                Tetap Terhubung dengan Kami
            </h2>
            <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto leading-relaxed transition-colors">
                Berlangganan untuk mendapatkan informasi terbaru seputar publikasi, jurnal, dan program kami. Kami tidak akan mengirim spam!
            </p>

            {{-- Success / Info message --}}
            @if(session('subscribe_success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg text-sm font-medium inline-block">
                    ✅ {{ session('subscribe_success') }}
                </div>
            @elseif(session('subscribe_info'))
                <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300 px-4 py-3 rounded-lg text-sm font-medium inline-block">
                    ℹ️ {{ session('subscribe_info') }}
                </div>
            @endif

            <form method="POST" action="{{ route('subscribe') }}" class="max-w-lg mx-auto">
                @csrf

                {{-- Email + Button --}}
                <div class="flex flex-col sm:flex-row gap-3 mb-4">
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="Masukkan alamat email Anda"
                           class="flex-1 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition @error('email') border-red-400 @enderror"
                           required>
                    <button type="submit"
                            class="flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-lg transition shadow-md hover:shadow-lg active:scale-95 text-sm whitespace-nowrap">
                        Kirim
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>

                @error('email')
                    <p class="text-red-500 text-xs mb-3 text-left">{{ $message }}</p>
                @enderror

                {{-- Consent checkbox --}}
                <label class="flex items-start gap-2.5 cursor-pointer text-left">
                    <input type="checkbox" name="consent" value="1"
                           class="mt-0.5 w-4 h-4 rounded border-gray-300 dark:border-slate-600 text-primary-600 focus:ring-primary-500 flex-shrink-0 @error('consent') border-red-400 @enderror">
                    <span class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        Saya menyetujui alamat email saya disimpan dan digunakan untuk menerima informasi terbaru dari Bina Karya Cendekia.
                    </span>
                </label>
                @error('consent')
                    <p class="text-red-500 text-xs mt-1 text-left">{{ $message }}</p>
                @enderror
            </form>

        </div>
    </section>

@endsection

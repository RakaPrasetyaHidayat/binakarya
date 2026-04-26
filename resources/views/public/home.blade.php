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
    <section class="relative overflow-hidden pt-16 sm:pt-20 md:pt-24 pb-16 sm:pb-20 lg:pb-24 transition-colors duration-300 -mt-1"
        :class="darkMode ? 'bg-slate-900 text-white' : 'bg-gray-50 text-slate-900'">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-12 lg:gap-16 items-center">
                
                <div class="flex flex-col justify-center order-1 lg:order-1 pt-0">
                    <div class="inline-block mb-3 sm:mb-4">
                        <span class="text-xs uppercase tracking-widest font-semibold px-0 py-0"
                            :class="darkMode ? 'text-gray-400' : 'text-gray-500'">
                            {{ $heroTagline }}
                        </span>
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-4 sm:mb-6 transition-colors whitespace-nowrap"
                        :class="darkMode ? 'text-white' : 'text-slate-900'">
                        {!! e($heroTitle) !!}
                    </h1>
                    
                    <p class="text-base sm:text-lg mb-4 sm:mb-6 leading-relaxed transition-colors"
                        :class="darkMode ? 'text-gray-200' : 'text-slate-600'">
                        {{ $siteSettings->get('hero_description', 'Menerbitkan karya-karya ilmiah berkualitas dan mendukung pengembangan literasi di Indonesia.') }}
                    </p>
                    
                    <p class="text-sm sm:text-base mb-6 sm:mb-8 leading-relaxed transition-colors"
                        :class="darkMode ? 'text-gray-300' : 'text-slate-600'">
                        Kami berkomitmen untuk menjadi jembatan antara peneliti, akademisi, dan masyarakat luas dalam menciptakan ekosistem publikasi yang inklusif, berintegritas, dan berdampak. Setiap karya yang kami terbitkan adalah hasil dari proses kurasi ketat untuk memastikan kualitas akademik dan kontribusi nyata terhadap pengembangan ilmu pengetahuan.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-3 justify-start">
                        <a href="{{ route('about') }}"
                            class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition shadow-md hover:shadow-lg">
                            <span>Tentang Kami</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
                
                <div class="flex justify-center lg:justify-end order-2 lg:order-2 h-96 sm:h-[500px] lg:h-[600px]">
                    <div class="w-full h-full rounded-3xl overflow-hidden shadow-2xl"
                        :class="darkMode ? 'bg-slate-800 border-2 border-slate-700' : 'bg-white border-2 border-gray-200'">
                        <div class="w-full h-full flex items-center justify-center text-center px-6 sm:px-8"
                            :class="darkMode ? 'bg-slate-800' : 'bg-gray-100'">
                            <div :class="darkMode ? 'text-slate-400' : 'text-slate-500'" class="text-sm sm:text-base leading-relaxed">
                                Konten visual hero dapat diperbaharui melalui CMS untuk memberi kesan profesional dan elegan.
                            </div>
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
        $bgClass = ($sectionCount++ % 2 == 0) ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800';
    @endphp
    <section class="py-12 sm:py-20 lg:py-24 {{ $bgClass }} transition-colors duration-300">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Centered Header --}}
            <div class="text-center mb-12 sm:mb-16">
                <span class="inline-block text-xs uppercase tracking-[0.3em] text-primary-600 dark:text-primary-400 font-bold mb-4 transition-colors px-4 py-2 bg-primary-50 dark:bg-primary-900/30 rounded-full">Tentang Kami</span>
                <h2 class="text-2xl sm:text-4xl lg:text-5xl font-sans font-bold text-gray-900 dark:text-white mb-6 transition-colors">
                    Bina Karya <span class="text-primary-600">Cendekia</span>
                </h2>
                <div class="w-24 h-1 bg-primary-500 mx-auto mb-6 rounded-full"></div>
                <p class="text-sm sm:text-lg text-gray-600 dark:text-gray-300 leading-relaxed transition-colors max-w-3xl mx-auto">
                    {!! $siteSettings->get('about_profile', 'Bina Karya Cendekia adalah lembaga penerbitan dan pengembangan ilmu pengetahuan yang berdedikasi untuk memajukan literasi dan penelitian di Indonesia melalui publikasi berkualitas.') !!}
                </p>
                <div class="mt-4">
                    <a href="{{ route('sitemap') }}" class="text-xs sm:text-sm text-primary-600 dark:text-primary-400 hover:underline">
                        Lihat Sitemap Dinamis
                    </a>
                </div>
            </div>

            {{-- Vision & Mission Cards - Centered --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-8 max-w-4xl mx-auto mb-12">
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 sm:p-8 shadow-lg dark:shadow-xl hover:shadow-xl dark:hover:shadow-2xl transition-all transform hover:-translate-y-1 text-center group border-t-4 border-primary-500">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-3">Visi Kami</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Menjadi pusat publikasi ilmiah yang memadukan kualitas akademik dan dampak sosial untuk kemajuan bangsa.</p>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 sm:p-8 shadow-lg dark:shadow-xl hover:shadow-xl dark:hover:shadow-2xl transition-all transform hover:-translate-y-1 text-center group border-t-4 border-blue-500">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-3">Misi Kami</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Mendukung penulis, peneliti, dan pembaca dengan layanan penerbitan dan literasi terintegrasi.</p>
                </div>
            </div>

            {{-- About Images Gallery --}}
            @if($aboutImages->count())
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
                @foreach($aboutImages as $image)
                    <div class="rounded-2xl overflow-hidden shadow-lg aspect-square group">
                        <img src="{{ asset('storage/' . $image) }}" alt="Tentang Kami" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
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
        @php $bgClass = ($sectionCount++ % 2 == 0) ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800'; @endphp
        <section class="py-12 sm:py-16 lg:py-24 {{ $bgClass }} transition-colors duration-300">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-8 sm:mb-12">
                    <span
                        class="text-xs uppercase tracking-[0.2em] {{ $sectionCount % 2 == 0 ? 'text-gray-500 dark:text-gray-400' : 'text-primary-600' }} font-semibold mb-2 sm:mb-3 block transition-colors">Layanan
                        Kami</span>
                    <h2 class="text-xl sm:text-3xl lg:text-4xl font-sans font-bold text-gray-900 dark:text-white mb-2 sm:mb-4 transition-colors">
                        Layanan Unggulan</h2>
                    <p class="text-xs sm:text-base text-gray-600 dark:text-gray-300 max-w-xl mx-auto transition-colors">Layanan kami menampilkan konten yang lebih visual dan elegan untuk memperkuat pesan profesional dan modern.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 max-w-6xl mx-auto" id="services-grid">
                    {{-- Card 1: Pelatihan Akademik --}}
                    <a href="https://visioncenter.id" target="_blank" rel="noopener noreferrer"
                        class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-md dark:shadow-xl hover:shadow-xl dark:hover:shadow-2xl transition-all group">
                        <div class="h-2 bg-gradient-to-r from-primary-500 to-primary-600"></div>
                        <div class="p-5 sm:p-8">
                            <div class="w-14 h-14 bg-primary-100 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform text-2xl">
                                📚
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition">
                                Pelatihan Akademik ↗
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mb-4">
                                Program pelatihan dan bimbingan akademik untuk persiapan karir dan pengembangan kompetensi.</p>
                            <span class="text-primary-600 dark:text-primary-400 text-sm font-medium inline-flex items-center gap-1">
                                Pelajari →
                            </span>
                        </div>
                    </a>

                    {{-- Card 2: Publikasi Buku --}}
                    <a href="{{ route('books.index') }}"
                        class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-md dark:shadow-xl hover:shadow-xl dark:hover:shadow-2xl transition-all group">
                        <div class="h-2 bg-gradient-to-r from-primary-500 to-primary-600"></div>
                        <div class="p-5 sm:p-8">
                            <div class="w-14 h-14 bg-primary-100 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform text-2xl">
                                📖
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition">
                                Publikasi Buku
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mb-4">
                                Penerbitan karya ilmiah berkualitas dengan standar akademik tinggi dan proses peer review.</p>
                            <span class="text-primary-600 dark:text-primary-400 text-sm font-medium inline-flex items-center gap-1">
                                Lihat Katalog →
                            </span>
                        </div>
                    </a>

                    {{-- Card 3: Publikasi Jurnal --}}
                    <a href="https://journal.binakaryacendekia.id" target="_blank" rel="noopener noreferrer"
                        class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-md dark:shadow-xl hover:shadow-xl dark:hover:shadow-2xl transition-all group">
                        <div class="h-2 bg-gradient-to-r from-primary-500 to-primary-600"></div>
                        <div class="p-5 sm:p-8">
                            <div class="w-14 h-14 bg-primary-100 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform text-2xl">
                                📝
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition">
                                Publikasi Jurnal ↗
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mb-4">
                                Jurnal ilmiah peer-reviewed dengan standar internasional untuk diseminasi penelitian.</p>
                            <span class="text-primary-600 dark:text-primary-400 text-sm font-medium inline-flex items-center gap-1">
                                Kunjungi Jurnal →
                            </span>
                        </div>
                    </a>
                </div>

                <div class="text-center mt-8 sm:mt-10">
                    <a href="{{ route('services.index') }}"
                        class="border-2 border-primary-600 text-primary-600 dark:text-primary-400 px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg hover:bg-primary-600 hover:text-white transition font-semibold text-sm sm:text-base"
                        style="display:inline-block;text-align:center;">
                        Lihat Semua Layanan &rarr;
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- Section 4: Buku Terbaru --}}
    @if($latestBooks->count())
        @php $bgClass = ($sectionCount++ % 2 == 0) ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800'; @endphp
        <section class="py-12 sm:py-16 lg:py-24 {{ $bgClass }} transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-8 sm:mb-12">
                    <span class="text-xs uppercase tracking-widest text-primary-600 font-semibold">Publikasi</span>
                    <h2 class="text-xl sm:text-3xl lg:text-4xl font-sans font-bold text-gray-900 dark:text-white mt-1 mb-2 sm:mb-4 transition-colors">
                        Koleksi Buku Terbaru</h2>
                    <p class="text-xs sm:text-base text-gray-600 dark:text-gray-300 max-w-xl mx-auto transition-colors">Koleksi publikasi ilmiah dan karya terbaik dari tim kami</p>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-5 lg:gap-6">
                    @foreach($latestBooks as $book)
                        <x-book-card :book="$book" />
                    @endforeach
                </div>

                <div class="text-center mt-8 sm:mt-10">
                    <a href="{{ route('books.index') }}"
                        class="bg-primary-600 text-white px-5 sm:px-7 py-2 sm:py-2.5 rounded-lg hover:bg-primary-700 transition shadow-md font-medium text-xs sm:text-base"
                        style="display:inline-block;text-align:center;">
                        Lihat Semua Katalog &rarr;
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- Section 5: Featured Quote --}}
    @php $bgClass = ($sectionCount++ % 2 == 0) ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800'; @endphp
    <section
        class="py-12 sm:py-16 lg:py-24 {{ $bgClass }} transition-colors duration-300">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-4 sm:mb-6 flex items-center justify-center">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-primary-300 dark:text-primary-600" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                    </svg>
                </div>
                <blockquote
                    class="text-lg sm:text-xl lg:text-2xl font-sans text-gray-800 dark:text-gray-100 italic leading-relaxed mb-4 sm:mb-6 transition-colors px-2">
                    "{{ $siteSettings->get('quote_text', 'Kurasi adalah bentuk kreasi baru. Kami percaya bahwa setiap karya ilmiah berhak mendapatkan perhatian dan distribusi yang layak.') }}"
                </blockquote>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 uppercase tracking-widest font-medium transition-colors">
                    — {{ $siteSettings->get('quote_author', 'Bina Karya Cendekia Foundation') }}</p>
                <div class="w-8 h-px bg-primary-300 dark:bg-primary-600 mx-auto mt-4 sm:mt-6 transition-colors"></div>
            </div>
        </div>
    </section>

    {{-- Section 6: Testimonials --}}
    @if($testimonials->count())
        @php $bgClass = ($sectionCount++ % 2 == 0) ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800'; @endphp
        <section class="py-10 sm:py-16 lg:py-24 {{ $bgClass }} transition-colors duration-300">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-7 sm:mb-12">
                    <span class="text-xs uppercase tracking-[0.2em] text-primary-600 font-semibold mb-2 block transition-colors">Testimoni</span>
                    <h2 class="text-xl sm:text-3xl lg:text-4xl font-sans font-bold text-gray-900 dark:text-white mb-2 sm:mb-4 transition-colors">
                        Kepercayaan dari Pengguna Kami</h2>
                    <p class="text-xs sm:text-base text-gray-600 dark:text-gray-300 max-w-xl mx-auto transition-colors">Ulasan pengguna kami tampil lebih ringkas, fokus, dan mudah dibaca di desktop maupun mobile.</p>
                </div>

                <x-testimonials-carousel :testimonials="$testimonials" />
            </div>
        </section>
    @endif

    {{-- Section 7: Blog Terbaru --}}
    @if($latestPosts->count())
        @php $bgClass = ($sectionCount++ % 2 == 0) ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800'; @endphp
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

    {{-- Section 8: CTA / Hubungi Kami --}}
    @php $bgClass = ($sectionCount++ % 2 == 0) ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800'; @endphp
    <section class="py-10 sm:py-16 lg:py-24 {{ $bgClass }} transition-colors duration-300">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-serif font-bold text-gray-900 dark:text-white mb-2 sm:mb-3 transition-colors">
                {{ $siteSettings->get('cta_title', 'Hubungi Kami') }}</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-6 sm:mb-8 max-w-md mx-auto leading-relaxed transition-colors">
                {{ $siteSettings->get('cta_description', 'Dapatkan pembaruan terbaru seputar jurnal, program yayasan, serta akses eksklusif ke literatur spesial kami.') }}
            </p>

            <div class="max-w-xs mx-auto">
                <a href="{{ $siteSettings->get('wa_number') ? 'https://wa.me/' . $siteSettings->get('wa_number') . '?text=Halo,%20saya%20ingin%20konsultasi%20gratis' : route('contact') }}" target="_blank" rel="noopener"
                    class="inline-block w-auto bg-green-600 text-white text-xs font-semibold px-5 py-2 rounded-lg hover:bg-green-700 transition-colors"
                    style="display:block;text-align:center;">
                    <span style="display:inline-flex;align-items:center;gap:5px;vertical-align:middle;">
                        <svg style="width:13px;height:13px;flex-shrink:0;" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        {{ $siteSettings->get('cta_button_text', 'Konsultasikan') }}
                    </span>
                </a>
            </div>
        </div>
    </section>

@endsection

@extends('layouts.public')

@section('title')
Tentang Kami
@endsection

@section('meta_description')
{{ $siteSettings->get('about_profile', '') }}
@endsection

@section('content')
{{-- Page Header — konsisten dengan halaman lain --}}
<div class="page-header">
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <defs><pattern id="grid-about" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/></pattern></defs>
            <rect width="100" height="100" fill="url(#grid-about)"/>
        </svg>
    </div>
    <div class="page-header-inner">
        <span class="page-header-tagline">Tentang Kami</span>
        <h1 class="page-header-title">Bina Karya <span class="text-yellow-300">Cendekia</span></h1>
        <p class="page-header-desc max-w-2xl">
            {!! strip_tags($siteSettings->get('about_profile', 'Lembaga penerbitan dan pengembangan ilmu pengetahuan yang berdedikasi untuk memajukan literasi dan penelitian di Indonesia.')) !!}
        </p>
    </div>
</div>

    @php $aboutSecCount = 0; @endphp

<div class="page-wrapper">

    {{-- Founder Profile --}}
    @php $bgClass = ($aboutSecCount++ % 2 == 0) ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800'; @endphp
    <section class="py-10 sm:py-14 lg:py-16 {{ $bgClass }} transition-colors duration-300">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-primary-700 dark:bg-slate-800 rounded-3xl overflow-hidden shadow-2xl">
            <div class="flex flex-col lg:flex-row">
                {{-- Founder Photo --}}
                <div class="lg:w-2/5 bg-primary-600 dark:bg-slate-700 p-8 lg:p-12 flex items-center justify-center">
                    @if($siteSettings->get('founder_photo'))
                        <div class="relative">
                            <div class="absolute inset-0 border-2 border-primary-400/30 dark:border-slate-500/30 rounded-2xl transform translate-x-4 translate-y-4"></div>
                            <img src="{{ asset('storage/' . $siteSettings->get('founder_photo')) }}"
                                 alt="{{ $siteSettings->get('founder_name', 'Pendiri') }}"
                                 class="relative w-64 h-80 sm:w-72 sm:h-96 object-cover rounded-2xl shadow-2xl">
                        </div>
                    @else
                        <div class="w-64 h-80 sm:w-72 sm:h-96 rounded-2xl border-2 border-dashed border-primary-300/60 dark:border-slate-500/60 bg-primary-500/20 dark:bg-slate-600/30 flex flex-col items-center justify-center text-center px-6">
                            <ion-icon name="image-outline" class="text-4xl text-primary-100 mb-3"></ion-icon>
                            <p class="text-sm font-semibold text-white">Box Foto Founder</p>
                            <p class="text-xs text-primary-100 mt-1">Admin dapat mengunggah foto melalui dashboard.</p>
                        </div>
                    @endif
                </div>

                {{-- Founder Info --}}
                <div class="lg:w-3/5 p-8 lg:p-12 flex flex-col justify-center">
                    <span class="text-xs uppercase tracking-widest text-primary-300 dark:text-primary-400 font-bold mb-3">THE FOUNDER</span>
                    <h3 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-4 leading-tight">
                        {{ $siteSettings->get('founder_name', 'Nama Founder Yayasan') }}
                    </h3>
                    <div class="w-16 h-1 bg-primary-400 rounded-full mb-6"></div>
                    <p class="text-primary-100 dark:text-gray-300 leading-relaxed text-sm sm:text-base">
                        {{ $siteSettings->get('founder_bio', 'Penjelasan founder dapat ditambahkan admin melalui dashboard halaman Tentang Kami.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

    {{-- Content Area --}}
    @php $bgClass = ($aboutSecCount++ % 2 == 0) ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800'; @endphp
    <div class="py-10 sm:py-14 lg:py-16 {{ $bgClass }} transition-colors duration-300" x-data="{ darkMode: document.documentElement.classList.contains('dark') }" @theme-changed.window="darkMode = $event.detail.isDark">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Visi & Misi Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12 sm:mb-16">
        {{-- Visi --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm dark:shadow-lg border border-gray-100 dark:border-slate-700 transition-all hover:shadow-md dark:hover:shadow-lg">
            <div class="w-12 h-12 bg-gradient-to-br from-primary-100 to-blue-100 dark:from-primary-900/30 dark:to-blue-900/30 rounded-xl flex items-center justify-center mb-4">
                <ion-icon name="eye-outline" class="text-primary-600 dark:text-primary-400 text-2xl"></ion-icon>
            </div>
            <h2 class="text-lg sm:text-2xl font-serif font-bold text-gray-900 dark:text-white mb-3 transition-colors">Visi Kami</h2>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 leading-relaxed transition-colors">
                {{ $siteSettings->get('about_vision', 'Menjadi lembaga penerbitan ilmiah terkemuka yang berkontribusi pada kemajuan ilmu pengetahuan dan literasi di Indonesia.') }}
            </p>
        </div>

        {{-- Misi --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm dark:shadow-lg border border-gray-100 dark:border-slate-700 transition-all hover:shadow-md dark:hover:shadow-lg">
            <div class="w-12 h-12 bg-gradient-to-br from-primary-100 to-blue-100 dark:from-primary-900/30 dark:to-blue-900/30 rounded-xl flex items-center justify-center mb-4">
                <ion-icon name="checkmark-circle-outline" class="text-primary-600 dark:text-primary-400 text-2xl"></ion-icon>
            </div>
            <h2 class="text-lg sm:text-2xl font-serif font-bold text-gray-900 dark:text-white mb-3 transition-colors">Misi Kami</h2>
            <div class="space-y-1 text-sm sm:text-base text-gray-600 dark:text-gray-300 leading-relaxed transition-colors">
                {!! nl2br(e($siteSettings->get('about_mission', "1. Menerbitkan buku-buku ilmiah berkualitas tinggi dengan standar akademik internasional.\n2. Mendukung peneliti dan akademisi Indonesia dalam diseminasi karya ilmiah.\n3. Memfasilitasi pengembangan literasi dan pengetahuan bagi masyarakat luas.\n4. Menjadi jembatan antara dunia akademik dan masyarakat.\n5. Memberdayakan komunitas melalui program-program pendidikan dan literasi."))) !!}
            </div>
        </div>
    </div>

        {{-- Alasan Memilih Kami --}}
        @php $bgClass = ($aboutSecCount++ % 2 == 0) ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800'; @endphp
        <section class="mb-16 sm:mb-20 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 sm:py-16 {{ $bgClass }} rounded-2xl">
        <div class="text-center mb-8 sm:mb-10">
            <h2 class="text-lg sm:text-2xl lg:text-3xl font-serif font-bold text-gray-900 dark:text-white mb-2 sm:mb-3 transition-colors">{{ $siteSettings->get('about_values_title', 'Alasan Memilih Kami') }}</h2>
            <p class="text-xs sm:text-base text-gray-600 dark:text-gray-300 max-w-2xl mx-auto transition-colors">Nilai utama yang membuat layanan kami terpercaya untuk kebutuhan Anda.</p>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div class="p-3 sm:p-5 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-slate-800 dark:to-slate-900 rounded-xl border border-gray-200 dark:border-slate-700 transition-all hover:shadow-md group">
                <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform shadow-md">
                    <ion-icon name="star-outline" class="text-white text-sm"></ion-icon>
                </div>
                <h3 class="font-semibold text-xs sm:text-sm text-gray-900 dark:text-white mb-1.5 transition-colors">{{ $siteSettings->get('about_value_1_title', 'Kualitas') }}</h3>
                <p class="text-[11px] sm:text-xs text-gray-600 dark:text-gray-400 leading-relaxed transition-colors">{{ $siteSettings->get('about_value_1_desc', 'Standar akademik tinggi dalam setiap publikasi') }}</p>
            </div>
            <div class="p-3 sm:p-5 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-slate-800 dark:to-slate-900 rounded-xl border border-gray-200 dark:border-slate-700 transition-all hover:shadow-md group">
                <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform shadow-md">
                    <ion-icon name="shield-checkmark-outline" class="text-white text-sm"></ion-icon>
                </div>
                <h3 class="font-semibold text-xs sm:text-sm text-gray-900 dark:text-white mb-1.5 transition-colors">{{ $siteSettings->get('about_value_2_title', 'Integritas') }}</h3>
                <p class="text-[11px] sm:text-xs text-gray-600 dark:text-gray-400 leading-relaxed transition-colors">{{ $siteSettings->get('about_value_2_desc', 'Etika dan kejujuran ilmiah yang kokoh') }}</p>
            </div>
            <div class="p-3 sm:p-5 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-slate-800 dark:to-slate-900 rounded-xl border border-gray-200 dark:border-slate-700 transition-all hover:shadow-md group">
                <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform shadow-md">
                    <ion-icon name="bulb-outline" class="text-white text-sm"></ion-icon>
                </div>
                <h3 class="font-semibold text-xs sm:text-sm text-gray-900 dark:text-white mb-1.5 transition-colors">{{ $siteSettings->get('about_value_3_title', 'Inovasi') }}</h3>
                <p class="text-[11px] sm:text-xs text-gray-600 dark:text-gray-400 leading-relaxed transition-colors">{{ $siteSettings->get('about_value_3_desc', 'Pemikiran kreatif dan solusi baru') }}</p>
            </div>
            <div class="p-3 sm:p-5 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-slate-800 dark:to-slate-900 rounded-xl border border-gray-200 dark:border-slate-700 transition-all hover:shadow-md group">
                <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform shadow-md">
                    <ion-icon name="globe-outline" class="text-white text-sm"></ion-icon>
                </div>
                <h3 class="font-semibold text-xs sm:text-sm text-gray-900 dark:text-white mb-1.5 transition-colors">{{ $siteSettings->get('about_value_4_title', 'Aksesibilitas') }}</h3>
                <p class="text-[11px] sm:text-xs text-gray-600 dark:text-gray-400 leading-relaxed transition-colors">{{ $siteSettings->get('about_value_4_desc', 'Ilmu pengetahuan untuk semua orang') }}</p>
            </div>
        </div>
    </section>

        {{-- Keanggotaan --}}
        @php $bgClass = ($aboutSecCount++ % 2 == 0) ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800'; @endphp
        <section class="mb-16 sm:mb-20 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 sm:py-16 {{ $bgClass }}">
        <div class="text-center mb-8 sm:mb-10">
            <h2 class="text-lg sm:text-2xl lg:text-3xl font-serif font-bold text-gray-900 dark:text-white mb-2 sm:mb-3 transition-colors">Keanggotaan</h2>
            <p class="text-xs sm:text-base text-gray-600 dark:text-gray-400 transition-colors">Struktur anggota yayasan yang dapat dikelola admin dari panel Team Member.</p>
        </div>

        @if($teamMembers->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                @foreach($teamMembers as $member)
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 sm:p-6 shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition-all group flex flex-col items-center">
                        {{-- Avatar --}}
                        <div class="mb-4">
                            @if($member->photo)
                                <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}"
                                     class="w-20 h-20 sm:w-24 sm:h-24 rounded-full mx-auto object-cover shadow-md border-4 border-primary-100 dark:border-primary-900/30 group-hover:border-primary-300 transition-all">
                            @else
                                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full mx-auto bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-md border-4 border-primary-100 dark:border-primary-900/30">
                                    <span class="text-white font-bold text-2xl sm:text-3xl">{{ substr($member->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        {{-- Info --}}
                        <h4 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white mb-1 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition leading-tight">
                            {{ $member->name }}
                        </h4>
                        <p class="text-primary-600 dark:text-primary-400 text-xs sm:text-sm font-semibold mb-2">
                            {{ $member->position }}
                        </p>
                        @if($member->bio)
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed line-clamp-3">
                            {{ $member->bio }}
                        </p>
                        @endif
                        {{-- Social links --}}
                        @if($member->linkedin || $member->email)
                        <div class="flex items-center gap-2 mt-3">
                            @if($member->linkedin)
                            <a href="{{ $member->linkedin }}" target="_blank" rel="noopener"
                               class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors"
                               :class="darkMode?'bg-slate-700 hover:bg-blue-600':'bg-gray-100 hover:bg-blue-600 hover:text-white'">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                            @endif
                            @if($member->email)
                            <a href="mailto:{{ $member->email }}"
                               class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors"
                               :class="darkMode?'bg-slate-700 hover:bg-primary-600':'bg-gray-100 hover:bg-primary-600 hover:text-white'">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-slate-800 rounded-xl p-5 sm:p-8 border border-dashed border-gray-300 dark:border-slate-700 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-300">Belum ada data anggota. Silakan tambahkan dari dashboard admin pada menu Team Member.</p>
            </div>
        @endif
    </section>

        {{-- Struktur Organisasi / Tabel Pengurus --}}
        @if($managers->count() || $siteSettings->get('about_org_structure'))
        @php $bgClass = ($aboutSecCount++ % 2 == 0) ? 'bg-white dark:bg-slate-900' : 'bg-slate-50 dark:bg-slate-800'; @endphp
        <section class="mb-12 sm:mb-16 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 sm:py-16 {{ $bgClass }}">
        <div class="text-center mb-10 sm:mb-14">
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-sans font-bold text-gray-900 dark:text-white mb-2 sm:mb-3 transition-colors">
                Struktur Organisasi & Pengurus
            </h2>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 transition-colors">
                Bertemu dengan para pemimpin dan penggerak perubahan kami
            </p>
        </div>

        @if($managers->count())
        <div class="mb-12 sm:mb-16">
            <div class="text-center mb-8 sm:mb-10">
                <span class="text-xs uppercase tracking-widest text-primary-600 dark:text-primary-400 font-bold mb-2 block">Pengurus</span>
                <h3 class="text-lg sm:text-xl lg:text-2xl font-sans font-bold text-gray-900 dark:text-white transition-colors">Struktur Pengurus Yayasan</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($managers->sortBy('sort_order') as $manager)
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm dark:shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-lg dark:hover:shadow-xl transition-all group">
                    {{-- Position Badge --}}
                    <span class="inline-block px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold rounded-full mb-4 uppercase tracking-wide">
                        {{ $manager->title ?? 'Staff' }}
                    </span>
                    
                    {{-- Name --}}
                    <h4 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition">
                        {{ $manager->name }}
                    </h4>
                    
                    {{-- Department --}}
                    @if($manager->department)
                    <p class="text-sm text-primary-600 dark:text-primary-400 font-medium mb-4 pb-4 border-b transition-colors duration-300" :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                        📍 {{ $manager->department }}
                    </p>
                    @endif
                    
                    {{-- Icon based on role --}}
                    <div class="flex justify-center">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-lg group-hover:scale-110 transition-transform">
                            @if(stripos($manager->title, 'ketua') !== false)
                                👔
                            @elseif(stripos($manager->title, 'wakil') !== false)
                                📋
                            @elseif(stripos($manager->title, 'bendahara') !== false)
                                💰
                            @elseif(stripos($manager->title, 'sekretaris') !== false)
                                ✍️
                            @else
                                👥
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </section>
    @endif

    {{-- About Organization Structure Text --}}
    @if($siteSettings->get('about_org_structure'))
    <section class="bg-white dark:bg-slate-800 rounded-xl p-6 sm:p-8 shadow-sm dark:shadow-lg border border-gray-100 dark:border-slate-700 transition-colors">
        <h2 class="text-lg sm:text-2xl font-serif font-bold text-gray-900 dark:text-white mb-4 sm:mb-6 transition-colors">Informasi Tambahan</h2>
        <div class="text-xs sm:text-base text-gray-600 dark:text-gray-300 leading-relaxed transition-colors">
            {!! nl2br(e($siteSettings->get('about_org_structure', ''))) !!}
        </div>
    </section>
    @endif
    </div>
</div>

</div>{{-- end page-wrapper --}}
@endsection

@extends('layouts.public')

@section('title')
{{ $book->title }}
@endsection

@section('meta_description')
{{ \Illuminate\Support\Str::limit($book->abstract ?? '', 200) }}
@endsection

@section('og_image')
{{ $book->cover_url }}
@endsection

@section('schema')
    <script type="application/ld+json">
        {!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endsection

@section('content')
@php
    $previewSource = null;
    $isPdfPreview = false;
    
    if ($book->preview_file) {
        $previewSource = $book->preview_url;
        $ext = pathinfo($book->preview_file, PATHINFO_EXTENSION);
        $isPdfPreview = strtolower($ext) === 'pdf';
    } elseif ($book->pdf_file) {
        $previewSource = $book->pdf_url;
        $isPdfPreview = true;
    } elseif ($book->getRawOriginal('preview_url')) {
        $previewSource = $book->getRawOriginal('preview_url');
        $isPdfPreview = str_ends_with(strtolower($previewSource), '.pdf') || str_contains(strtolower($previewSource), 'drive.google.com');
    }
@endphp

{{-- Navbar clearance + main page wrapper with matching background --}}
<div class="min-h-screen transition-colors duration-300"
    :class="darkMode ? 'bg-slate-900' : 'bg-gray-50'">

    {{-- Main content container --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 sm:pt-24 lg:pt-32 pb-16">

        {{-- Breadcrumb --}}
        <nav class="text-sm mb-6 transition-colors duration-300" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">
            <a href="{{ route('books.index') }}" class="transition-colors duration-200" :class="darkMode ? 'hover:text-primary-400' : 'hover:text-primary-600'">Katalog Buku</a>
            <span class="mx-2">/</span>
            <span class="transition-colors duration-300" :class="darkMode ? 'text-gray-300' : 'text-gray-800'">{{ \Illuminate\Support\Str::limit($book->title, 50) }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left Column: Cover + WhatsApp Button --}}
            <div class="lg:col-span-1">
                {{-- Book Cover --}}
                <div class="aspect-[3/4] rounded-lg overflow-hidden shadow-md transition-colors duration-300 mb-4"
                    :class="darkMode ? 'bg-gray-800 shadow-none' : 'bg-gray-100'">
                    @if($book->cover_url)
                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center transition-colors duration-300"
                            :class="darkMode ? 'bg-gradient-to-br from-gray-800 to-gray-700' : 'bg-gradient-to-br from-blue-50 to-blue-100'">
                            <svg class="w-16 h-16 transition-colors duration-300"
                                :class="darkMode ? 'text-gray-600' : 'text-blue-300'"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- WhatsApp Button Below Cover --}}
                @if($book->wa_link)
                <a href="{{ $book->wa_link }}" target="_blank" rel="noopener"
                   class="flex items-center justify-center gap-2 w-full text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-300 bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Beli via WhatsApp
                </a>
                @endif
            </div>

            {{-- Right Column: Details & Actions --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Category Badge --}}
                @if($book->category)
                    <div class="inline-flex items-center gap-2 text-xs font-medium px-3 py-1.5 rounded-full transition-colors duration-300"
                        :class="darkMode ? 'bg-primary-950 text-primary-300' : 'bg-blue-100 text-blue-700'">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        {{ $book->category->name }}
                    </div>
                @endif

                {{-- Title & Author --}}
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-3 leading-tight transition-colors duration-300"
                        :class="darkMode ? 'text-gray-100' : 'text-gray-900'">{{ $book->title }}</h1>
                    <p class="text-lg transition-colors duration-300"
                        :class="darkMode ? 'text-gray-400' : 'text-gray-600'">{{ $book->author }}</p>
                </div>

                {{-- Metadata Grid — tiap item selalu menutup div-nya sendiri --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-5">

                    @if($book->isbn)
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5 transition-colors duration-300"
                            :class="darkMode ? 'text-gray-500' : 'text-gray-400'"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-widest mb-0.5 transition-colors duration-300"
                                :class="darkMode ? 'text-gray-500' : 'text-gray-400'">ISBN</p>
                            <p class="text-sm font-semibold transition-colors duration-300 break-all"
                                :class="darkMode ? 'text-gray-200' : 'text-gray-900'">{{ $book->isbn }}</p>
                        </div>
                    </div>
                    @endif

                    @if($book->doi)
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5 transition-colors duration-300"
                            :class="darkMode ? 'text-gray-500' : 'text-gray-400'"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-widest mb-0.5 transition-colors duration-300"
                                :class="darkMode ? 'text-gray-500' : 'text-gray-400'">DOI</p>
                            <a href="https://doi.org/{{ $book->doi }}" target="_blank" rel="noopener"
                               class="text-sm font-semibold hover:underline transition-colors duration-200 inline-flex items-center gap-1 break-all"
                               :class="darkMode ? 'text-primary-400 hover:text-primary-300' : 'text-primary-600 hover:text-primary-700'">
                                {{ $book->doi }}
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($book->published_year)
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5 transition-colors duration-300"
                            :class="darkMode ? 'text-gray-500' : 'text-gray-400'"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-widest mb-0.5 transition-colors duration-300"
                                :class="darkMode ? 'text-gray-500' : 'text-gray-400'">Tahun Terbit</p>
                            <p class="text-sm font-semibold transition-colors duration-300"
                                :class="darkMode ? 'text-gray-200' : 'text-gray-900'">{{ $book->published_year }}</p>
                        </div>
                    </div>
                    @endif

                    @if($book->edition)
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5 transition-colors duration-300"
                            :class="darkMode ? 'text-gray-500' : 'text-gray-400'"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-widest mb-0.5 transition-colors duration-300"
                                :class="darkMode ? 'text-gray-500' : 'text-gray-400'">Edisi</p>
                            <p class="text-sm font-semibold transition-colors duration-300"
                                :class="darkMode ? 'text-gray-200' : 'text-gray-900'">{{ $book->edition }}</p>
                        </div>
                    </div>
                    @endif

                    @if($book->price)
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5 transition-colors duration-300"
                            :class="darkMode ? 'text-gray-500' : 'text-gray-400'"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-widest mb-0.5 transition-colors duration-300"
                                :class="darkMode ? 'text-gray-500' : 'text-gray-400'">Harga</p>
                            <p class="text-sm font-semibold transition-colors duration-300"
                                :class="darkMode ? 'text-gray-200' : 'text-gray-900'">Rp {{ number_format($book->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endif

                </div>{{-- end metadata grid --}}

                {{-- PDF Preview Section --}}
                @if($previewSource)
                <div class="pt-6 border-t transition-colors duration-300"
                    :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                    <div class="rounded-2xl shadow-soft border transition-all duration-300 overflow-hidden"
                        :class="darkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-gray-100 hover:shadow-soft-lg'">
                        <div class="px-6 py-4 border-b flex items-center justify-between transition-colors"
                            :class="darkMode ? 'border-slate-800 bg-slate-800/50' : 'border-gray-100 bg-gray-50/50'">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zM9 13h2v2H9v-2zm0 4h6v-1H9v1zm5-8H9v2h5V9z"/>
                                    </svg>
                                </div>
                                <h2 class="text-sm font-bold uppercase tracking-wider transition-colors"
                                    :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                    {{ $isPdfPreview ? 'Preview PDF Buku' : 'Preview' }}
                                </h2>
                            </div>
                            <a href="{{ $previewSource }}" target="_blank" rel="noopener"
                               class="text-xs font-bold text-primary-600 dark:text-primary-400 uppercase tracking-widest hover:underline flex items-center gap-1.5">
                                Buka Full <ion-icon name="open-outline"></ion-icon>
                            </a>
                        </div>
                        <div class="relative">
                            @php
                                $isRawHtml = str_contains($previewSource, '<') && str_contains($previewSource, '>');
                            @endphp
                            
                            @if($isRawHtml)
                                <div class="w-full flex items-center justify-center p-4">
                                    <div class="max-w-full overflow-hidden">
                                        {!! $previewSource !!}
                                    </div>
                                </div>
                            @elseif($isPdfPreview)
                                <iframe src="{{ $previewSource }}" class="w-full block"
                                    style="height: 450px; min-height: 350px;"
                                    title="Preview {{ $book->title }}" loading="lazy"></iframe>
                            @else
                                <div class="flex items-center justify-center bg-gray-100 dark:bg-slate-950 p-4">
                                    <img src="{{ $previewSource }}" alt="Preview {{ $book->title }}"
                                        class="max-w-full h-auto rounded shadow-sm border border-gray-200 dark:border-slate-800 transition-transform duration-500 hover:scale-[1.02]">
                                </div>
                            @endif
                        </div>
                        <div class="mt-4 p-4 rounded-xl transition-colors"
                            :class="darkMode ? 'bg-slate-800/50' : 'bg-blue-50/50'">
                            <p class="text-xs text-center leading-relaxed"
                                :class="darkMode ? 'text-gray-400' : 'text-gray-500'">
                                <span class="font-bold text-primary-600 dark:text-primary-400">Tips:</span>
                                Gunakan desktop untuk pratinjau PDF yang lebih baik atau
                                <a href="{{ $previewSource }}" target="_blank" rel="noopener"
                                   class="font-bold underline hover:text-primary-500 transition-colors">klik di sini untuk membuka di tab baru</a>.
                            </p>
                        </div>
                    </div>
                </div>
                @else
                <div class="pt-6 border-t transition-colors duration-300 opacity-70"
                    :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                    <div class="rounded-2xl border border-dashed transition-all duration-300 overflow-hidden"
                        :class="darkMode ? 'bg-slate-900 border-slate-700' : 'bg-white border-gray-200'">
                        <div class="px-6 py-4 border-b flex items-center gap-3 transition-colors"
                            :class="darkMode ? 'border-slate-800 bg-slate-800/50' : 'border-gray-100 bg-gray-50/50'">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zM9 13h2v2H9v-2zm0 4h6v-1H9v1zm5-8H9v2h5V9z"/>
                                </svg>
                            </div>
                            <h2 class="text-sm font-bold uppercase tracking-wider transition-colors"
                                :class="darkMode ? 'text-gray-400' : 'text-gray-400'">Pratinjau Belum Tersedia</h2>
                        </div>
                        <div class="py-16 flex flex-col items-center justify-center text-center px-6">
                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-800/50 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium mb-1"
                                :class="darkMode ? 'text-gray-400' : 'text-gray-500'">Belum ada PDF atau gambar pratinjau</p>
                            <p class="text-xs max-w-xs mx-auto"
                                :class="darkMode ? 'text-gray-500' : 'text-gray-400'">Preview akan muncul secara otomatis setelah Anda mengunggah file atau menautkan URL di dashboard admin.</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Abstract Section --}}
                @if($book->abstract)
                <div class="pt-6 border-t transition-colors duration-300"
                    :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                    <h3 class="text-lg font-bold mb-4 transition-colors"
                        :class="darkMode ? 'text-gray-300' : 'text-gray-900'">Abstrak</h3>
                    <div class="prose prose-sm max-w-none leading-relaxed max-h-32 overflow-y-auto pr-2 transition-colors duration-300 book-abstract-scroll"
                        :class="darkMode ? 'prose-invert text-gray-400' : 'text-gray-600'">
                        {!! $book->abstract !!}
                    </div>
                </div>
                @endif

                {{-- Keywords Section --}}
                @if($book->keywords)
                <div class="pt-6 border-t transition-colors duration-300"
                    :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                    <h3 class="text-lg font-bold mb-4 transition-colors"
                        :class="darkMode ? 'text-gray-300' : 'text-gray-900'">Kata Kunci</h3>
                    <div class="flex flex-wrap gap-3">
                        @foreach(explode(',', $book->keywords) as $keyword)
                            <span class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300"
                                :class="darkMode ? 'bg-slate-800 text-slate-300 border border-slate-700 hover:border-slate-600' : 'bg-gray-100 text-gray-700 border border-gray-300 hover:bg-gray-200'">
                                {{ trim($keyword) }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Buy Buttons (Stay in right column) --}}
                <div class="space-y-3 pt-6">
                    @if($book->shopee_url)
                    <a href="{{ $book->shopee_url }}" target="_blank" rel="noopener"
                       class="flex items-center justify-center gap-2 w-full text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-300 bg-orange-500 hover:bg-orange-600 dark:bg-orange-600 dark:hover:bg-orange-700">
                        🛒 Beli di Shopee
                    </a>
                    @endif

                    @if($book->tokopedia_url)
                    <a href="{{ $book->tokopedia_url }}" target="_blank" rel="noopener"
                       class="flex items-center justify-center gap-2 w-full text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-300 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800">
                        🛍️ Beli di Tokopedia
                    </a>
                    @endif

                    @if($book->custom_url)
                    <a href="{{ $book->custom_url }}" target="_blank" rel="noopener"
                       class="flex items-center justify-center gap-2 w-full text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-300 bg-primary-600 hover:bg-primary-700 dark:bg-primary-700 dark:hover:bg-primary-800">
                        {{ $book->custom_url_label ?? 'Beli Sekarang' }}
                    </a>
                    @endif

                    @if($previewSource)
                    <a href="{{ $previewSource }}" target="_blank" rel="noopener"
                       class="flex items-center justify-center gap-2 w-full border-2 font-semibold py-3 px-4 rounded-lg transition-colors duration-300 border-primary-600 text-primary-600 hover:bg-primary-50 dark:border-primary-400 dark:text-primary-400 dark:hover:bg-primary-950">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ $isPdfPreview ? 'Baca / Preview PDF' : 'Lihat Preview' }}
                    </a>
                    @endif

                    @if($book->pdf_file)
                    <a href="{{ $book->pdf_url }}" download
                       class="flex items-center justify-center gap-2 w-full border-2 font-semibold py-3 px-4 rounded-lg transition-colors duration-300 border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download PDF
                    </a>
                    @endif
                </div>

            </div>{{-- end right column --}}
        </div>{{-- end main grid --}}

        {{-- Related Books (Full width) --}}
        @php
            $relatedBooks = \App\Models\Book::where('category_id', $book->category_id)
                ->where('id', '!=', $book->id)
                ->where('is_published', true)
                ->take(4)
                ->get();
        @endphp

        @if($relatedBooks->count() > 0)
        <div class="mt-16 pt-10 border-t transition-colors duration-300"
            :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold transition-colors duration-300"
                    :class="darkMode ? 'text-gray-100' : 'text-gray-900'">Buku Terkait Lainnya</h3>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($relatedBooks as $relBook)
                    <x-book-card :book="$relBook" />
                @endforeach
            </div>
        </div>
        @endif

    </div>{{-- end content container --}}

</div>{{-- end page wrapper --}}


<style>
    .book-abstract-scroll::-webkit-scrollbar {
        width: 4px;
    }
    .book-abstract-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .book-abstract-scroll::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 20px;
    }
    .dark .book-abstract-scroll::-webkit-scrollbar-thumb {
        background-color: #475569;
    }
    .book-abstract-scroll {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    .dark .book-abstract-scroll {
        scrollbar-color: #475569 transparent;
    }
</style>
@endsection

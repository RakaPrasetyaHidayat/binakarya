@extends('layouts.public')

@section('title', 'Beranda')

@section('content')
    {{-- Navbar clearance gap matching first section --}}
    <div class="pt-16 sm:pt-20 lg:pt-24 transition-colors duration-300"
        :class="darkMode ? 'bg-slate-900' : 'bg-gray-50'">
    </div>

    {{-- Koleksi Buku --}}
    <section class="pb-12 sm:pb-16 lg:pb-20 pt-6 sm:pt-8 md:pt-10 lg:pt-12 bg-gray-50 dark:bg-slate-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-14">
                <span class="text-xs uppercase tracking-widest text-primary-600 font-semibold">Publikasi</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mt-2">Koleksi Buku</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($latestBooks as $book)
                    <a href="{{ route('books.show', $book->slug) }}"
                        class="group bg-white dark:bg-slate-800 rounded-xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200">
                        <div class="aspect-[3/2] overflow-hidden bg-gray-100 dark:bg-slate-700">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}"
                                    alt="{{ $book->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <ion-icon name="book-outline" class="text-5xl text-gray-300 dark:text-gray-600"></ion-icon>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="font-semibold text-gray-900 dark:text-white text-sm sm:text-base line-clamp-2 mb-2 group-hover:text-primary-600 transition-colors">
                                {{ $book->title }}
                            </h3>
                            @if($book->abstract)
                                <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm line-clamp-2">
                                    {{ Str::limit(strip_tags($book->abstract), 100) }}
                                </p>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm">Oleh {{ $book->author ?? 'Unknown' }}</p>
                            @endif
                        </div>
                    </a>
                @empty
                    <p class="col-span-full text-center text-gray-500 dark:text-gray-400 py-10">
                        Belum ada buku tersedia.
                    </p>
                @endforelse
            </div>

            @if($latestBooks->isNotEmpty())
                <div class="text-center mt-12">
                    <a href="{{ route('books.index') }}"
                        class="cta-center inline-flex gap-2 bg-primary-600 text-white px-6 py-2.5 rounded-lg hover:bg-primary-700 transition font-medium text-sm">
                        Lihat Lebih Banyak <ion-icon name="arrow-forward-outline"></ion-icon>
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- Blog Terbaru --}}
    <section class="py-16 sm:py-20 lg:py-24 bg-white dark:bg-slate-800 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-14">
                <span class="text-xs uppercase tracking-widest text-primary-600 font-semibold">Artikel</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mt-2">Blog Terbaru</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($latestPosts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}"
                        class="group bg-white dark:bg-slate-800 rounded-xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200">
                        <div class="aspect-[16/9] overflow-hidden bg-gray-100 dark:bg-slate-700">
                            @if($post->featured_image)
                                <img src="{{ asset('storage/' . $post->featured_image) }}"
                                    alt="{{ $post->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <ion-icon name="newspaper-outline" class="text-5xl text-gray-300 dark:text-gray-600"></ion-icon>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="font-semibold text-gray-900 dark:text-white text-sm sm:text-base line-clamp-2 mb-1 group-hover:text-primary-600 transition-colors">
                                {{ $post->title }}
                            </h3>
                            @if($post->excerpt)
                                <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm line-clamp-2">
                                    {{ Str::limit($post->excerpt, 100) }}
                                </p>
                            @endif
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                                {{ $post->published_at?->translatedFormat('d M Y') }}
                            </p>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full text-center text-gray-500 dark:text-gray-400 py-10">
                        Belum ada artikel tersedia.
                    </p>
                @endforelse
            </div>

            @if($latestPosts->isNotEmpty())
                <div class="text-center mt-12">
                    <a href="{{ route('blog.index') }}"
                        class="cta-center inline-flex gap-2 bg-primary-600 text-white px-6 py-2.5 rounded-lg hover:bg-primary-700 transition font-medium text-sm">
                        Lihat Lebih Banyak <ion-icon name="arrow-forward-outline"></ion-icon>
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- Testimonials Section --}}
    <section class="py-16 sm:py-20 lg:py-24 bg-gray-50 dark:bg-slate-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-14">
                <span class="text-xs uppercase tracking-widest text-primary-600 font-semibold">Testimoni</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mt-2">Apa Kata Mereka</h2>
            </div>
            <x-testimonials-carousel />
        </div>
    </section>

    {{-- Contact Section --}}
    <section class="py-16 sm:py-20 lg:py-24 bg-white dark:bg-slate-800 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('public.contact')
        </div>
    </section>
@endsection

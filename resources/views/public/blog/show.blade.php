@extends('layouts.public')

@section('title')
{{ $post->meta_title ?? $post->title }}
@endsection

@section('meta_description')
{{ $post->meta_description ?? $post->excerpt }}
@endsection

@section('og_image')
{{ $post->featured_image_url }}
@endsection

@section('meta_scholar')
    <meta name="citation_title" content="{{ $post->title }}">
    <meta name="citation_author" content="{{ $post->user->name }}">
    <meta name="citation_publication_date" content="{{ $post->published_at->format('Y/m/d') }}">
    <meta name="citation_abstract" content="{{ $post->excerpt }}">
    <meta name="citation_language" content="id">
@endsection

@section('schema')
    <script type="application/ld+json">
        {!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endsection

@section('content')
@php
    preg_match('/<img[^>]+src=["\'"]([^"\']+)["\'"]/i', $post->body ?? '', $firstImageMatch);
    $bodyFirstImage = $firstImageMatch[1] ?? null;
    $articleVisual = $post->detail_image_url ?: $post->featured_image_url ?: $bodyFirstImage;
@endphp

{{-- Page wrapper — navbar clearance shares same bg as content --}}
<div class="min-h-screen transition-colors duration-300"
    :class="darkMode ? 'bg-slate-900' : 'bg-white'">

    {{-- Navbar clearance gap --}}
    <div class="pt-16 sm:pt-20 lg:pt-24"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-16">

        <article>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">

                {{-- Main Content --}}
                <div class="lg:col-span-2">

                    {{-- Breadcrumb --}}
                    <nav class="text-sm mb-6 transition-colors duration-300"
                        :class="darkMode ? 'text-gray-400' : 'text-gray-500'">
                        <a href="{{ route('blog.index') }}" class="transition-colors duration-200"
                            :class="darkMode ? 'hover:text-primary-400' : 'hover:text-primary-600'">Blog</a>
                        <span class="mx-2">/</span>
                        <span class="transition-colors duration-300"
                            :class="darkMode ? 'text-gray-300' : 'text-gray-800'">{{ Str::limit($post->title, 50) }}</span>
                    </nav>

                    @if($post->category)
                        <span class="inline-block text-xs font-medium px-3 py-1 rounded-full mb-3 transition-colors duration-300"
                            :class="darkMode ? 'bg-primary-950 text-primary-300' : 'bg-primary-100 text-primary-700'">
                            {{ $post->category->name }}
                        </span>
                    @endif

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-4 leading-tight transition-colors duration-300"
                        :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $post->title }}</h1>

                    {{-- Featured thumbnail below title --}}
                    @if($articleVisual)
                    <div class="mb-5 rounded-xl overflow-hidden border transition-colors duration-300 max-h-[280px]"
                        :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                        <img src="{{ $articleVisual }}" alt="{{ $post->title }}"
                            class="w-full h-auto max-h-[280px] object-cover">
                    </div>
                    @endif

                    {{-- Meta info --}}
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-sm mb-6 pb-6 border-b transition-colors duration-300"
                        :class="darkMode ? 'border-gray-700 text-gray-400' : 'border-gray-200 text-gray-500'">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold text-xs">
                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                            </div>
                            <span class="font-medium" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">{{ $post->user->name }}</span>
                        </div>
                        <span>·</span>
                        <time datetime="{{ $post->published_at?->toISOString() }}">{{ $post->published_at?->format('d F Y') }}</time>
                        <span>·</span>
                        <span>{{ ceil(str_word_count(strip_tags($post->body)) / 200) }} menit baca</span>
                    </div>

                    @if($post->detail_image_url && $post->detail_image_url !== $articleVisual)
                        <div class="mb-6 rounded-2xl overflow-hidden border transition-colors duration-300"
                            :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                            <img src="{{ $post->detail_image_url }}" alt="{{ $post->title }}"
                                class="w-full h-auto object-cover">
                        </div>
                    @endif

                    {{-- Table of Contents --}}
                    @php
                        preg_match_all('/<h([2-3])[^>]*>(.*?)<\/h\1>/i', $post->body, $headings, PREG_SET_ORDER);
                    @endphp
                    @if(count($headings) > 2)
                    <div class="mb-8 p-5 rounded-xl border transition-colors duration-300"
                        :class="darkMode ? 'bg-slate-800/50 border-slate-700' : 'bg-gray-50 border-gray-200'">
                        <h3 class="text-sm font-bold uppercase tracking-wider mb-3 transition-colors"
                            :class="darkMode ? 'text-gray-300' : 'text-gray-700'">📑 Daftar Isi</h3>
                        <ul class="space-y-2">
                            @foreach($headings as $index => $heading)
                                @php
                                    $level = $heading[1];
                                    $text = strip_tags($heading[2]);
                                    $slug = Str::slug($text);
                                @endphp
                                <li class="{{ $level == '3' ? 'ml-4' : '' }}">
                                    <a href="#{{ $slug }}"
                                       class="text-sm transition-colors hover:underline flex items-center gap-2"
                                       :class="darkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-600 hover:text-primary-600'">
                                        <span class="text-xs opacity-50">{{ $level == '2' ? '◆' : '◇' }}</span>
                                        {{ $text }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Article body --}}
                    <div class="prose prose-lg max-w-none leading-relaxed blog-content transition-colors duration-300"
                        :class="darkMode ? 'prose-invert' : ''">
                        {!! $post->body !!}
                    </div>

                    <style>
                        .blog-content { color: inherit; }
                        .blog-content p { margin-bottom: 1.5rem; }
                        .blog-content h2 { margin-top: 2.5rem; margin-bottom: 1rem; font-weight: 700; font-size: 1.5rem; scroll-margin-top: 100px; }
                        .blog-content h3 { margin-top: 1.5rem; margin-bottom: 0.75rem; font-weight: 600; font-size: 1.25rem; scroll-margin-top: 100px; }
                        .blog-content ul, .blog-content ol { margin-bottom: 1.5rem; padding-left: 1.5rem; }
                        .blog-content li { margin-bottom: 0.5rem; }
                        .blog-content img { border-radius: 0.75rem; margin-top: 2rem; margin-bottom: 2rem; }
                        .blog-content blockquote { margin: 2rem 0; padding: 1rem 1.5rem; border-left: 4px solid #2563eb; background: rgba(37, 99, 235, 0.08); border-radius: 0.5rem; }
                        .dark .blog-content blockquote { border-color: #7C3AED; background-color: rgba(124, 58, 237, 0.1); }
                        .dark .blog-content { color: rgb(229, 231, 235); }
                        .dark .blog-content h2, .dark .blog-content h3 { color: rgb(243, 244, 246); }
                        .dark .blog-content strong { color: rgb(243, 244, 246); }
                        .blog-content table { border-collapse: collapse; width: 100%; margin-bottom: 1.5rem; }
                        .blog-content th, .blog-content td { border: 1px solid #e5e7eb; padding: 0.75rem; }
                        .dark .blog-content th, .dark .blog-content td { border-color: #374151; }
                        .blog-content th { background-color: #f9fafb; font-weight: 600; }
                        .dark .blog-content th { background-color: #1f2937; }
                        .blog-content code { background: #f3f4f6; padding: 0.2rem 0.4rem; border-radius: 0.25rem; font-size: 0.875em; }
                        .dark .blog-content code { background: #374151; }
                        .blog-content pre { background: #1f2937; color: #e5e7eb; padding: 1rem; border-radius: 0.5rem; overflow-x: auto; margin-bottom: 1.5rem; }
                    </style>

                    {{-- Tags / Share --}}
                    <div class="mt-10 pt-6 border-t transition-colors duration-300"
                        :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium transition-colors"
                                    :class="darkMode ? 'text-gray-400' : 'text-gray-500'">Bagikan:</span>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}"
                                   target="_blank" rel="noopener"
                                   class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors"
                                   :class="darkMode ? 'bg-slate-700 text-blue-400 hover:bg-slate-600' : 'bg-blue-50 text-blue-600 hover:bg-blue-100'">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}"
                                   target="_blank" rel="noopener"
                                   class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors"
                                   :class="darkMode ? 'bg-slate-700 text-sky-400 hover:bg-slate-600' : 'bg-sky-50 text-sky-500 hover:bg-sky-100'">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . route('blog.show', $post->slug)) }}"
                                   target="_blank" rel="noopener"
                                   class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors"
                                   :class="darkMode ? 'bg-slate-700 text-green-400 hover:bg-slate-600' : 'bg-green-50 text-green-600 hover:bg-green-100'">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('blog.show', $post->slug)) }}"
                                   target="_blank" rel="noopener"
                                   class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors"
                                   :class="darkMode ? 'bg-slate-700 text-blue-400 hover:bg-slate-600' : 'bg-blue-50 text-blue-700 hover:bg-blue-100'">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Author Box --}}
                    <div class="mt-8 p-6 rounded-xl border transition-colors duration-300"
                        :class="darkMode ? 'bg-slate-800/50 border-slate-700' : 'bg-gray-50 border-gray-200'">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold text-xl flex-shrink-0">
                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="font-semibold transition-colors"
                                    :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $post->user->name }}</h4>
                                <p class="text-sm transition-colors mt-1"
                                    :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Penulis di {{ $siteSettings->get('site_name', 'Bina Karya Cendekia') }}</p>
                                <p class="text-xs transition-colors mt-2"
                                    :class="darkMode ? 'text-gray-500' : 'text-gray-500'">Artikel dipublikasikan pada {{ $post->published_at?->format('d F Y') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Related Articles --}}
                    @if($relatedPosts && $relatedPosts->count() > 0)
                    <section class="mt-12 pt-8 border-t transition-colors duration-300"
                        :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                        <h2 class="text-xl font-bold mb-6 transition-colors duration-300"
                            :class="darkMode ? 'text-white' : 'text-gray-900'">📚 Artikel Terkait</h2>
                        <div class="space-y-4">
                            @foreach($relatedPosts as $relatedPost)
                            <article class="flex gap-4 p-4 rounded-xl border transition-all duration-300 hover:shadow-md"
                                :class="darkMode ? 'border-gray-700 hover:border-gray-600 bg-slate-800/30' : 'border-gray-200 hover:border-gray-300 bg-white'">
                                @if($relatedPost->featured_image_url)
                                    <img src="{{ $relatedPost->featured_image_url }}" alt="{{ $relatedPost->title }}"
                                        class="w-24 h-20 object-cover rounded-lg flex-shrink-0">
                                @else
                                    <div class="w-24 h-20 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors"
                                        :class="darkMode ? 'bg-slate-700' : 'bg-gray-100'">
                                        <span class="text-2xl">📖</span>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs transition-colors duration-300 mb-1"
                                        :class="darkMode ? 'text-gray-500' : 'text-gray-500'">
                                        {{ $relatedPost->published_at?->format('d F Y') }}
                                    </div>
                                    <h3 class="font-semibold line-clamp-2 transition-colors duration-300"
                                        :class="darkMode ? 'text-white' : 'text-gray-900'">
                                        <a href="{{ route('blog.show', $relatedPost->slug) }}"
                                           class="transition-colors"
                                           :class="darkMode ? 'hover:text-primary-400' : 'hover:text-primary-600'">
                                            {{ $relatedPost->title }}
                                        </a>
                                    </h3>
                                    <p class="text-sm line-clamp-2 mt-1 transition-colors duration-300"
                                        :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                        {{ $relatedPost->excerpt }}
                                    </p>
                                </div>
                            </article>
                            @endforeach
                        </div>
                    </section>
                    @endif

                </div>{{-- end main content --}}

                {{-- Sidebar --}}
                <aside class="lg:col-span-1">
                    <div class="lg:sticky lg:top-28 space-y-6">

                        {{-- Author Card --}}
                        <div class="p-5 rounded-xl border transition-colors duration-300"
                            :class="darkMode ? 'bg-slate-800/50 border-slate-700' : 'bg-white border-gray-200'">
                            <h3 class="text-sm font-bold uppercase tracking-wider mb-4 transition-colors"
                                :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Penulis</h3>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold text-lg">
                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-sm transition-colors"
                                        :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $post->user->name }}</p>
                                    <p class="text-xs transition-colors"
                                        :class="darkMode ? 'text-gray-400' : 'text-gray-500'">Kontributor</p>
                                </div>
                            </div>
                        </div>

                        {{-- Categories --}}
                        @if($post->category)
                        <div class="p-5 rounded-xl border transition-colors duration-300"
                            :class="darkMode ? 'bg-slate-800/50 border-slate-700' : 'bg-white border-gray-200'">
                            <h3 class="text-sm font-bold uppercase tracking-wider mb-4 transition-colors"
                                :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Kategori</h3>
                            <a href="{{ route('blog.index', ['category' => $post->category_id]) }}"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                               :class="darkMode ? 'bg-primary-900/30 text-primary-400 hover:bg-primary-900/50' : 'bg-primary-50 text-primary-700 hover:bg-primary-100'">
                                <ion-icon name="folder-outline"></ion-icon>
                                {{ $post->category->name }}
                            </a>
                        </div>
                        @endif

                        {{-- Share --}}
                        <div class="p-5 rounded-xl border transition-colors duration-300"
                            :class="darkMode ? 'bg-slate-800/50 border-slate-700' : 'bg-white border-gray-200'">
                            <h3 class="text-sm font-bold uppercase tracking-wider mb-4 transition-colors"
                                :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Bagikan</h3>
                            <div class="flex gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}"
                                   target="_blank" rel="noopener"
                                   class="flex-1 py-2.5 rounded-lg text-center text-sm font-medium transition-colors"
                                   :class="darkMode ? 'bg-slate-700 text-blue-400 hover:bg-slate-600' : 'bg-blue-50 text-blue-600 hover:bg-blue-100'">
                                    Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}"
                                   target="_blank" rel="noopener"
                                   class="flex-1 py-2.5 rounded-lg text-center text-sm font-medium transition-colors"
                                   :class="darkMode ? 'bg-slate-700 text-sky-400 hover:bg-slate-600' : 'bg-sky-50 text-sky-500 hover:bg-sky-100'">
                                    X
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . route('blog.show', $post->slug)) }}"
                                   target="_blank" rel="noopener"
                                   class="flex-1 py-2.5 rounded-lg text-center text-sm font-medium transition-colors"
                                   :class="darkMode ? 'bg-slate-700 text-green-400 hover:bg-slate-600' : 'bg-green-50 text-green-600 hover:bg-green-100'">
                                    WhatsApp
                                </a>
                            </div>
                        </div>

                        {{-- CTA --}}
                        <div class="p-5 rounded-xl bg-primary-600 text-white">
                            <h3 class="text-sm font-bold uppercase tracking-wider mb-2">Butuh Bantuan?</h3>
                            <p class="text-sm text-primary-100 mb-4">Konsultasikan kebutuhan publikasi Anda dengan tim kami.</p>
                            <a href="{{ route('contact') }}"
                               class="block w-full py-2.5 rounded-lg bg-white text-primary-600 text-sm font-semibold text-center hover:bg-primary-50 transition">
                                Hubungi Kami
                            </a>
                        </div>

                    </div>
                </aside>{{-- end sidebar --}}

            </div>{{-- end grid --}}
        </article>

    </div>{{-- end container --}}

</div>{{-- end page wrapper --}}
@endsection

@php use Illuminate\Support\Str; @endphp

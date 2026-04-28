@props(['post'])

<article class="bg-white dark:bg-slate-800 rounded-xl overflow-hidden shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 border border-gray-200 dark:border-gray-700 flex flex-col h-full group">

    {{-- Thumbnail --}}
    @php
        $imgSrc = $post->featured_image
            ? asset('storage/' . $post->featured_image)
            : ($post->featured_image_url ?? null);
    @endphp

    @if($imgSrc)
        <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden bg-gray-100 dark:bg-gray-700 aspect-[16/9] flex-shrink-0">
            <img src="{{ $imgSrc }}" alt="{{ $post->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                 loading="lazy">
        </a>
    @else
        <div class="aspect-[16/9] flex-shrink-0 bg-gradient-to-br from-primary-50 to-blue-50 dark:from-primary-900/20 dark:to-blue-900/20 flex items-center justify-center">
            <svg class="w-10 h-10 text-primary-200 dark:text-primary-900/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
    @endif

    {{-- Body --}}
    <div class="p-4 flex-1 flex flex-col">

        {{-- Meta --}}
        <div class="flex items-center gap-2 mb-2.5 flex-wrap">
            @if($post->category)
                <span class="bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider leading-none">
                    {{ $post->category->name }}
                </span>
            @endif
            <span class="text-gray-400 dark:text-gray-500 text-[10px] flex items-center gap-1 leading-none">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $post->published_at?->format('d M Y') ?? '-' }}
            </span>
        </div>

        {{-- Title --}}
        <h2 class="text-sm sm:text-[15px] font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 leading-snug group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
        </h2>

        {{-- Excerpt --}}
        @if($post->excerpt)
            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mb-3 leading-relaxed flex-1">
                {{ $post->excerpt }}
            </p>
        @else
            <div class="flex-1"></div>
        @endif

        {{-- Footer --}}
        <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700 mt-auto">
            <div class="flex items-center gap-1.5 min-w-0">
                <div class="w-5 h-5 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center flex-shrink-0">
                    <span class="text-[9px] font-bold text-primary-600 dark:text-primary-400">
                        {{ strtoupper(substr($post->user->name ?? 'A', 0, 1)) }}
                    </span>
                </div>
                <span class="text-[11px] font-medium text-gray-500 dark:text-gray-400 truncate">
                    {{ $post->user->name ?? 'Penulis' }}
                </span>
            </div>
            <a href="{{ route('blog.show', $post->slug) }}"
               class="text-primary-600 dark:text-primary-400 text-[11px] font-bold flex items-center gap-0.5 flex-shrink-0 ml-2 hover:gap-1.5 transition-all">
                Baca
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</article>

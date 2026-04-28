@props(['book'])

<article class="w-full bg-white dark:bg-slate-800 rounded-xl overflow-hidden shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 border border-gray-200 dark:border-gray-700 flex flex-col h-full group" style="min-width:0;">

    {{-- Cover --}}
    <div class="relative overflow-hidden w-full bg-gray-100 dark:bg-gray-700 aspect-[3/4] flex-shrink-0">
        @if($book->cover_url)
            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}"
                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500"
                 loading="lazy">
            <div class="absolute inset-0 shadow-[inset_0_0_30px_rgba(0,0,0,0.08)] pointer-events-none"></div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-primary-600/15 to-primary-800/15 flex flex-col items-center justify-center p-4 text-center">
                <svg class="w-10 h-10 text-primary-400/30 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span class="text-[9px] font-bold text-primary-500/40 uppercase tracking-wider">No Cover</span>
            </div>
        @endif
        <a href="{{ route('books.show', $book->slug) }}" class="absolute inset-0 z-10" aria-label="Lihat detail buku {{ $book->title }}"></a>
    </div>

    {{-- Body --}}
    <div class="p-3 sm:p-4 flex-1 flex flex-col min-w-0">

        {{-- Category + Year --}}
        <div class="flex items-center gap-1.5 mb-2 flex-wrap">
            @if($book->category)
                <span class="bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider leading-none">
                    {{ $book->category->name }}
                </span>
            @endif
            @if($book->published_year)
                <span class="text-gray-400 dark:text-gray-500 text-[10px] flex items-center gap-0.5 leading-none">
                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $book->published_year }}
                </span>
            @endif
        </div>

        {{-- Title --}}
        <h3 class="text-sm sm:text-[15px] font-bold text-gray-900 dark:text-white mb-1.5 line-clamp-2 leading-snug group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
            <a href="{{ route('books.show', $book->slug) }}">{{ $book->title }}</a>
        </h3>

        {{-- Abstract --}}
        @if($book->abstract)
            <p class="text-[11px] text-gray-500 dark:text-gray-400 line-clamp-2 mb-3 leading-relaxed flex-1">
                {{ strip_tags($book->abstract) }}
            </p>
        @else
            <div class="flex-1"></div>
        @endif

        {{-- Footer --}}
        <div class="mt-auto pt-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between gap-2">
            <span class="text-[11px] font-medium text-gray-500 dark:text-gray-400 italic truncate" title="{{ $book->author }}">
                {{ $book->author ?? 'Penulis' }}
            </span>
            <a href="{{ route('books.show', $book->slug) }}"
               class="inline-flex items-center gap-0.5 text-primary-600 dark:text-primary-400 text-[11px] font-bold flex-shrink-0 hover:gap-1.5 transition-all">
                Lihat
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</article>

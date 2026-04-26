@props(['book'])

<article class="w-full bg-white dark:bg-slate-800 rounded-xl overflow-hidden shadow-sm dark:shadow-md hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700 flex flex-col h-full group" style="min-width: 0;">
    <div class="relative overflow-hidden w-full bg-gray-100 dark:bg-gray-700 aspect-[3/4]">
        @if($book->cover_url)
            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-105 transition duration-500" loading="lazy">
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-primary-600/20 to-primary-800/20 flex items-center justify-center">
                <svg class="w-10 h-10 text-primary-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
        @endif
        <a href="{{ route('books.show', $book->slug) }}" class="absolute inset-0 z-10"></a>
    </div>
    
    <div class="p-4 flex-1 flex flex-col min-w-0">
        <div class="flex items-center gap-2 mb-3 flex-wrap">
            @if($book->category)
                <span class="bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">{{ $book->category->name }}</span>
            @endif
            <span class="text-gray-400 dark:text-gray-500 text-[10px] flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>{{ $book->published_year ?? '-' }}</span>
            </span>
            @if($book->isbn)
                <span class="text-gray-400 dark:text-gray-500 text-[10px] flex items-center gap-1 border-l pl-2 border-gray-200 dark:border-gray-700">
                    <span class="font-bold">ISBN:</span>
                    <span>{{ $book->isbn }}</span>
                </span>
            @endif
        </div>
        
        <h3 class="text-sm md:text-base font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 leading-snug min-h-[2.5rem]">
            <a href="{{ route('books.show', $book->slug) }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                {{ $book->title }}
            </a>
        </h3>
        
        <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between gap-3">
            <span class="text-[10px] md:text-xs font-medium text-gray-500 dark:text-gray-400 italic truncate flex-1">{{ $book->author ?? 'Penulis' }}</span>
            <a href="{{ route('books.show', $book->slug) }}" class="flex items-center gap-1 text-primary-600 dark:text-primary-400 text-xs font-bold hover:gap-1.5 transition-all flex-shrink-0">
                Lihat <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</article>

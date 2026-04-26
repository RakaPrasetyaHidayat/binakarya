@props(['post'])

<article class="bg-white dark:bg-slate-800 rounded-xl overflow-hidden shadow-sm dark:shadow-lg hover:shadow-md dark:hover:shadow-lg dark:hover:shadow-primary-500/10 hover:-translate-y-1 transition duration-300 border border-gray-200 dark:border-gray-700 flex flex-col h-full group">
    @if($post->featured_image)
        <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden bg-gray-100 dark:bg-gray-700 h-36 sm:h-52 md:h-64 lg:h-72">
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500" loading="lazy">
        </a>
    @elseif($post->featured_image_url)
        <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden bg-gray-100 dark:bg-gray-700 h-36 sm:h-52 md:h-64 lg:h-72">
            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500" loading="lazy">
        </a>
    @else
        <div class="h-36 sm:h-52 md:h-64 lg:h-72 bg-gradient-to-br from-primary-50 to-blue-50 dark:from-primary-900/20 dark:to-blue-900/20 flex items-center justify-center">
            <svg class="w-10 h-10 sm:w-14 sm:h-14 md:w-16 md:h-16 text-primary-200 dark:text-primary-900/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
    @endif
    
    <div class="p-2.5 sm:p-4 flex-1 flex flex-col">
        <div class="flex items-center gap-1.5 mb-2 flex-wrap">
            @if($post->category)
                <span class="bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-[8px] sm:text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">{{ $post->category->name }}</span>
            @endif
            <span class="text-gray-400 dark:text-gray-500 text-[8px] sm:text-[10px] flex items-center gap-0.5">
                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $post->published_at?->format('d M Y') ?? '-' }}
            </span>
        </div>
        
        <h2 class="text-[10px] sm:text-sm md:text-base font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 leading-snug min-h-[2.4em]">
            <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                {{ $post->title }}
            </a>
        </h2>
        
        <p class="text-gray-600 dark:text-gray-400 text-[9px] sm:text-xs line-clamp-2 mb-3 leading-relaxed flex-1">{{ $post->excerpt }}</p>
        
        <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-1.5 min-w-0">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name ?? 'Author') }}&background=EBF4FF&color=7F9CF5" alt="{{ $post->user->name ?? 'Author' }}" class="w-4 h-4 sm:w-5 sm:h-5 rounded-full flex-shrink-0" loading="lazy">
                <span class="text-[8px] sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 truncate">{{ $post->user->name ?? 'Penulis' }}</span>
            </div>
            <a href="{{ route('blog.show', $post->slug) }}" class="text-primary-600 dark:text-primary-400 text-[9px] sm:text-xs font-bold hover:gap-2 transition-all flex items-center gap-0.5 flex-shrink-0 ml-1">
                Baca <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</article>

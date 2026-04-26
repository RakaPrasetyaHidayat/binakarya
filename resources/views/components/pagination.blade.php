{{-- Clean Pagination Component --}}

@if($paginator->hasPages())
    <div class="mt-10 pt-8 border-t transition-colors duration-300"
         :class="darkMode ? 'border-gray-700' : 'border-gray-200'">

        {{-- Single row: prev + numbers + next --}}
        <div class="flex items-center justify-center gap-1.5">

            {{-- Previous --}}
            @if($paginator->onFirstPage())
                <button disabled
                        class="w-9 h-9 flex items-center justify-center rounded-lg cursor-not-allowed opacity-40 transition-colors"
                        :class="darkMode ? 'bg-gray-800 text-gray-500' : 'bg-gray-100 text-gray-400'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="w-9 h-9 flex items-center justify-center rounded-lg font-medium transition-all"
                   :class="darkMode ? 'bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            @endif

            {{-- First page --}}
            @if($paginator->currentPage() > 2)
                <a href="{{ $paginator->url(1) }}"
                   class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-medium transition-all"
                   :class="darkMode ? 'bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'">1</a>
            @endif

            @if($paginator->currentPage() > 3)
                <span class="w-9 h-9 flex items-center justify-center text-sm"
                      :class="darkMode ? 'text-gray-500' : 'text-gray-400'">…</span>
            @endif

            {{-- Page range --}}
            @foreach($paginator->getUrlRange(max(1, $paginator->currentPage() - 1), min($paginator->lastPage(), $paginator->currentPage() + 1)) as $page => $url)
                @if($page == $paginator->currentPage())
                    <span class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-bold text-white bg-primary-600 dark:bg-primary-500 shadow-md">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                       class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-medium transition-all"
                       :class="darkMode ? 'bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            @if($paginator->currentPage() < $paginator->lastPage() - 2)
                <span class="w-9 h-9 flex items-center justify-center text-sm"
                      :class="darkMode ? 'text-gray-500' : 'text-gray-400'">…</span>
            @endif

            {{-- Last page --}}
            @if($paginator->currentPage() < $paginator->lastPage() - 1)
                <a href="{{ $paginator->url($paginator->lastPage()) }}"
                   class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-medium transition-all"
                   :class="darkMode ? 'bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'">
                    {{ $paginator->lastPage() }}
                </a>
            @endif

            {{-- Next --}}
            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="w-9 h-9 flex items-center justify-center rounded-lg font-medium transition-all"
                   :class="darkMode ? 'bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <button disabled
                        class="w-9 h-9 flex items-center justify-center rounded-lg cursor-not-allowed opacity-40 transition-colors"
                        :class="darkMode ? 'bg-gray-800 text-gray-500' : 'bg-gray-100 text-gray-400'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            @endif
        </div>

        {{-- Page info --}}
        <div class="mt-4 text-center text-xs font-medium transition-colors"
             :class="darkMode ? 'text-gray-500' : 'text-gray-400'">
            Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
        </div>
    </div>
@endif

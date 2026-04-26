{{-- Testimonials Carousel Component - Clean Design --}}
@props(['testimonials' => []])

@if($testimonials->count())
    <div x-data="{ currentIndex: 0, total: {{ $testimonials->count() }} }" x-init="setInterval(() => currentIndex = (currentIndex + 1) % total, 6500)" class="relative">
        {{-- Carousel Container --}}
        <div class="overflow-hidden">
            <div class="flex transition-transform duration-500 ease-out" :style="`transform: translateX(-${currentIndex * 100}%)`">
                @foreach($testimonials as $testimonial)
                    <div class="w-full flex-shrink-0 px-1 sm:px-4">
                        <div class="max-w-3xl mx-auto bg-white dark:bg-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-8 lg:p-10 shadow-sm sm:shadow-lg dark:shadow-xl border border-gray-100 dark:border-slate-700">
                            {{-- Quote Text --}}
                            <div class="mb-5 sm:mb-6">
                                <p class="text-sm sm:text-lg text-gray-700 dark:text-gray-300 leading-relaxed text-center">
                                    "{{ $testimonial->content }}"
                                </p>
                            </div>
                            
                            {{-- Author Info with Photo --}}
                            <div class="flex items-center justify-center gap-3 sm:gap-4">
                                @if($testimonial->photo)
                                    <img src="{{ $testimonial->photo_url }}" alt="{{ $testimonial->name }}" class="w-11 h-11 sm:w-14 sm:h-14 rounded-full object-cover shadow-md ring-2 ring-primary-100 dark:ring-primary-900/40">
                                @else
                                    <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-md ring-2 ring-primary-100 dark:ring-primary-900/40">
                                        <span class="text-white font-bold text-base sm:text-lg">{{ substr($testimonial->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="text-left">
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-sm sm:text-base">{{ $testimonial->name }}</h4>
                                    @if($testimonial->position || $testimonial->organization)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            @if($testimonial->position){{ $testimonial->position }}@endif
                                            @if($testimonial->position && $testimonial->organization) • @endif
                                            @if($testimonial->organization){{ $testimonial->organization }}@endif
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Navigation --}}
        <div class="flex items-center justify-center gap-3 sm:gap-6 mt-5 sm:mt-8">
            {{-- Previous --}}
            <button @click="currentIndex = currentIndex > 0 ? currentIndex - 1 : total - 1" 
                    class="w-9 h-9 sm:w-10 sm:h-10 rounded-full flex items-center justify-center bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition shadow-sm">
                ←
            </button>

            {{-- Dot Indicators - Show All --}}
            <div class="flex gap-2">
                <template x-for="i in total" :key="i">
                    <button @click="currentIndex = i - 1" 
                            class="w-2.5 h-2.5 rounded-full transition-all"
                            :class="currentIndex === i - 1 ? 'bg-primary-600 w-6' : 'bg-gray-300 dark:bg-gray-600 hover:bg-gray-400'"></button>
                </template>
            </div>

            {{-- Next --}}
            <button @click="currentIndex = (currentIndex + 1) % total" 
                    class="w-10 h-10 rounded-full flex items-center justify-center bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition shadow-sm">
                →
            </button>
        </div>
    </div>
@endif

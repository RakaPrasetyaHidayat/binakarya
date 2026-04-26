{{-- Manager/Organizer Section Component --}}

@if($managers->count())
    <div class="py-12 sm:py-16 lg:py-20 border-t transition-colors duration-300"
         :class="darkMode ? 'border-t border-gray-700' : 'border-t border-gray-200'">
        
        <div class="text-center mb-10 sm:mb-14">
            <h3 class="text-xl sm:text-2xl lg:text-3xl font-sans font-bold text-gray-900 dark:text-white mb-2 sm:mb-3 transition-colors">
                Struktur Organisasi
            </h3>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 transition-colors">
                Bertemu dengan para pemimpin dan penggerak perubahan kami
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($managers->sortBy('sort_order') as $manager)
                <div class="bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-sm dark:shadow-lg hover:shadow-md dark:hover:shadow-lg dark:hover:shadow-primary-500/10 transition-all border border-gray-100 dark:border-gray-700 text-center p-6 sm:p-8 group">
                    
                    {{-- Photo/Avatar --}}
                    @if($manager->photo)
                        <div class="mb-5 sm:mb-6">
                            <img src="{{ $manager->photo_url }}" alt="{{ $manager->name }}" 
                                 class="w-24 h-24 sm:w-28 sm:h-28 rounded-full mx-auto object-cover shadow-md border-4 border-primary-100 dark:border-primary-900/30 group-hover:border-primary-300 dark:group-hover:border-primary-700 transition-all">
                        </div>
                    @else
                        <div class="mb-5 sm:mb-6">
                            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full mx-auto bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-md border-4 border-primary-100 dark:border-primary-900/30 group-hover:border-primary-300 dark:group-hover:border-primary-700 transition-all">
                                <span class="text-white font-bold text-3xl sm:text-4xl">{{ substr($manager->name, 0, 1) }}</span>
                            </div>
                        </div>
                    @endif

                    {{-- Name --}}
                    <h4 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-1 transition-colors group-hover:text-primary-600 dark:group-hover:text-primary-400">
                        {{ $manager->name }}
                    </h4>

                    {{-- Title & Department --}}
                    @if($manager->title)
                        <p class="text-sm sm:text-base font-semibold text-primary-600 dark:text-primary-400 mb-1 transition-colors">
                            {{ $manager->title }}
                        </p>
                    @endif

                    @if($manager->department)
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-4 sm:mb-5 transition-colors">
                            {{ $manager->department }}
                        </p>
                    @endif

                    {{-- Biography --}}
                    @if($manager->biography)
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed transition-colors">
                            {{ Str::limit($manager->biography, 150) }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif

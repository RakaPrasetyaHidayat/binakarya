@php
    $data = $block['data'] ?? [];
    $title = $data['title'] ?? '';
    $subtitle = $data['subtitle'] ?? '';
    $backgroundImage = $data['background_image'] ?? '';
@endphp

<section class="relative rounded-2xl overflow-hidden border border-gray-200 dark:border-slate-700">
    @if($backgroundImage)
        <div class="absolute inset-0">
            <img src="{{ $backgroundImage }}" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/50"></div>
        </div>
    @endif
    <div class="relative px-6 py-12 sm:px-10 sm:py-16 {{ $backgroundImage ? 'text-white' : 'bg-gray-50 dark:bg-slate-800 text-gray-900 dark:text-white' }}">
        @if($title)
            <h2 class="text-2xl sm:text-3xl font-bold leading-tight">{{ $title }}</h2>
        @endif
        @if($subtitle)
            <p class="mt-3 text-sm sm:text-base {{ $backgroundImage ? 'text-gray-100' : 'text-gray-600 dark:text-slate-300' }}">{{ $subtitle }}</p>
        @endif
    </div>
</section>

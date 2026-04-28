@php
    $data = $block['data'] ?? [];
@endphp

<section class="rounded-2xl p-6 sm:p-8 bg-primary-50 dark:bg-primary-900/20 border border-primary-100 dark:border-primary-900/40">
    @if(!empty($data['title']))
        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $data['title'] }}</h3>
    @endif
    @if(!empty($data['description']))
        <p class="mt-2 text-sm text-gray-600 dark:text-slate-300">{{ $data['description'] }}</p>
    @endif
    @if(!empty($data['button_url']) && !empty($data['button_label']))
        <a href="{{ $data['button_url'] }}" class="inline-flex mt-4 px-4 py-2 rounded-lg bg-primary-600 text-white font-semibold hover:bg-primary-700 transition-colors">
            {{ $data['button_label'] }}
        </a>
    @endif
</section>
